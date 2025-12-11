<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ClearsRelatedCache;
use App\Models\Tna;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class QuizQuestionController extends Controller
{
    use ClearsRelatedCache;

    public function index()
    {
        $page = request()->get('page', 1);
        $tnas = Cache::remember('admin_quiz_questions_index_page_' . $page, 60, function () {
            return Tna::withCount('quizQuestions')->paginate(10);
        });
        
        return view('admin.quiz_questions.index', compact('tnas'));
    }

    public function show(Tna $tna)
    {
        $tnaId = $tna->id;
        
        $questions = Cache::remember("admin_quiz_questions_tna_{$tnaId}", 60, function () use ($tna) {
            return $tna->quizQuestions()
                ->with('quizAnswers')
                ->orderBy('question_number')
                ->get();
        });
            
        return view('admin.quiz_questions.form', compact('tna', 'questions'));
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Pertanyaan');
        $sheet->setCellValue('C1', 'Jawaban 1');
        $sheet->setCellValue('D1', 'Jawaban 2');
        $sheet->setCellValue('E1', 'Jawaban 3');
        $sheet->setCellValue('F1', 'Jawaban 4');
        $sheet->setCellValue('G1', 'Kunci Jawaban (1-4)');

        // Contoh Data
        $sheet->setCellValue('A2', '1');
        $sheet->setCellValue('B2', 'Apa ibukota Indonesia?');
        $sheet->setCellValue('C2', 'Jakarta');
        $sheet->setCellValue('D2', 'Bandung');
        $sheet->setCellValue('E2', 'Surabaya');
        $sheet->setCellValue('F2', 'Medan');
        $sheet->setCellValue('G2', '1');

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_soal_kuis.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function importExcel(Request $request, Tna $tna)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048'
        ]);

        try {
            DB::transaction(function () use ($request, $tna) {
                // 1. HAPUS SEMUA SOAL LAMA (Mode Replace)
                $tna->quizQuestions()->delete();

                // 2. PROSES FILE BARU
                $file = $request->file('excel_file');
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                array_shift($rows); // Skip header

                foreach ($rows as $row) {
                    if (empty($row[0]) || empty($row[1])) continue;

                    $question = QuizQuestion::create([
                        'tna_id' => $tna->id,
                        'question' => $row[1],
                        'question_number' => $row[0],
                    ]);

                    for ($i = 2; $i <= 5; $i++) {
                        if (!empty($row[$i])) {
                            QuizAnswer::create([
                                'quiz_question_id' => $question->id,
                                'answer' => $row[$i],
                                'answer_order' => $i - 1,
                                'is_correct' => ($row[6] ?? 1) == ($i - 1),
                            ]);
                        }
                    }
                }
            });
            
            // BERSIH-BERSIH CACHE
            $this->flushQuizCache($tna);

            return redirect()->route('admin.quiz_questions.show', $tna)
                ->with('success', 'Soal kuis berhasil diproses.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor soal: ' . $e->getMessage());
        }
    }

    public function destroy(QuizQuestion $question)
    {
        // Ambil TNA sebelum delete untuk keperluan cache clearing
        $tna = $question->tna; 
        
        $question->delete();
        
        // BERSIH-BERSIH CACHE
        if ($tna) {
            $this->flushQuizCache($tna);
        }
        
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function destroyAll(Tna $tna)
    {
        $tna->quizQuestions()->delete();
        
        // BERSIH-BERSIH CACHE
        $this->flushQuizCache($tna);
        
        return back()->with('success', 'Semua soal berhasil dihapus.');
    }

    /**
     * PRIVATE HELPER: Centralized Quiz Cache Clearing
     */
    private function flushQuizCache(Tna $tna)
    {
        // 1. Clear Global Admin List
        $this->clearRelatedCaches([
            'admin_quiz_questions_index_page_*', // List TNA di menu Bank Soal
            'admin_quiz_results_index',          // List Hasil Quiz (jaga-jaga)
        ]);

        // 2. Clear Laporan Spesifik TNA
        $this->clearRelatedCaches([
            "admin_quiz_questions_tna_{$tna->id}", // List Soal Detail
            "admin_quiz_pretest_tna_{$tna->id}",   // Laporan Pretest
            "admin_quiz_posttest_tna_{$tna->id}",  // Laporan Posttest
        ]);

        // 3. Clear Cache Peserta
        // Biar peserta yang lagi buka menu Evaluasi langsung dapat update soal
        foreach ($tna->registrations as $registration) {
            $this->clearUserCaches($registration->user_id, $tna->id);
        }
    }
}