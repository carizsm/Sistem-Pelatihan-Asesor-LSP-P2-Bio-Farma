<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tna;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
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
            ->with('quizAnswers')
            ->orderBy('question_number')
            ->get();
            
        return view('admin.quiz_questions.form', compact('tna', 'questions'));
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Question Number');
        $sheet->setCellValue('B1', 'Question Text');
        $sheet->setCellValue('C1', 'Answer 1');
        $sheet->setCellValue('D1', 'Answer 2');
        $sheet->setCellValue('E1', 'Answer 3');
        $sheet->setCellValue('F1', 'Answer 4');
        $sheet->setCellValue('G1', 'Correct Answer (1-4)');

        // Example row
        $sheet->setCellValue('A2', '1');
        $sheet->setCellValue('B2', 'Contoh pertanyaan kuis?');
        $sheet->setCellValue('C2', 'Jawaban A');
        $sheet->setCellValue('D2', 'Jawaban B');
        $sheet->setCellValue('E2', 'Jawaban C');
        $sheet->setCellValue('F2', 'Jawaban D');
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
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            foreach ($rows as $row) {
                if (empty($row[0]) || empty($row[1])) continue; // Skip empty rows

                $question = QuizQuestion::create([
                    'tna_id' => $tna->id,
                    'question' => $row[1],
                    'question_number' => $row[0],
                ]);

                // Create answers (columns C to F = index 2 to 5)
                $answerNumber = 1;
                for ($i = 2; $i <= 5; $i++) {
                    if (!empty($row[$i])) {
                        QuizAnswer::create([
                            'quiz_question_id' => $question->id,
                            'answer' => $row[$i],
                            'answer_order' => $answerNumber,
                            'is_correct' => ($row[6] ?? 1) == $answerNumber,
                        ]);
                        $answerNumber++;
                    }
                }
            }

            return redirect()->route('admin.quiz-questions.form', $tna)
                ->with('success', 'Soal kuis berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor soal: ' . $e->getMessage());
        }
    }
}
