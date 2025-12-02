<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tna;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class QuizQuestionController extends Controller
{
    public function index()
    {
        $tnas = Tna::withCount('quizQuestions')->get();
        return view('admin.quiz_questions.index', compact('tnas'));
    }

    public function show(Tna $tna)
    {
        $questions = $tna->quizQuestions()
            ->with('quizAnswers') // Pastikan nama method relasi di model benar ('quizAnswers' atau 'answers')
            ->orderBy('question_number')
            ->get();
            
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

        // Contoh
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
            // Gunakan Transaction untuk integritas data
            DB::transaction(function () use ($request, $tna) {
                
                // 1. HAPUS SEMUA SOAL LAMA (Mode Replace)
                $tna->quizQuestions()->delete();

                // 2. PROSES FILE BARU
                $file = $request->file('excel_file');
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Skip header row
                array_shift($rows);

                foreach ($rows as $row) {
                    if (empty($row[0]) || empty($row[1])) continue;

                    $question = QuizQuestion::create([
                        'tna_id' => $tna->id,
                        'question' => $row[1],
                        'question_number' => $row[0],
                    ]);

                    // Jawaban (Kolom C-F / Index 2-5)
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

            return redirect()->route('admin.quiz_questions.show', $tna)
                ->with('success', 'Soal kuis berhasil diproses.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor soal: ' . $e->getMessage());
        }
    }

    // Method Baru: Hapus Satu Soal
    public function destroy(QuizQuestion $question)
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // Method Baru: Hapus Semua Soal
    public function destroyAll(Tna $tna)
    {
        $tna->quizQuestions()->delete();
        return back()->with('success', 'Semua soal berhasil dihapus.');
    }
}