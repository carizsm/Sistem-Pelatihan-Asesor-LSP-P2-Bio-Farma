<?php

namespace Database\Seeders;

use App\Models\Tna;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tnas = Tna::all();

        if ($tnas->isEmpty()) {
            $this->command->warn('No TNAs found. Please run TNASeeder first.');
            return;
        }

        foreach ($tnas as $tna) {
            // Cek apakah TNA sudah punya quiz
            if ($tna->quizQuestions()->count() > 0) {
                $this->command->info("TNA '{$tna->name}' already has quiz questions. Skipping...");
                continue;
            }

            // Buat 10 soal quiz per TNA
            for ($i = 1; $i <= 10; $i++) {
                $question = QuizQuestion::factory()->create([
                    'tna_id' => $tna->id,
                    'question_number' => $i,
                    'question' => "Soal nomor {$i}: " . fake()->sentence(8) . '?',
                ]);

                // Tentukan jawaban mana yang benar (random antara 1-4)
                $correctAnswerOrder = rand(1, 4);

                // Buat 4 jawaban (A, B, C, D)
                for ($j = 1; $j <= 4; $j++) {
                    $answerLetter = chr(64 + $j); // A, B, C, D
                    $isCorrect = ($j === $correctAnswerOrder);

                    QuizAnswer::factory()->create([
                        'quiz_question_id' => $question->id,
                        'answer_order' => $j,
                        'answer' => "Pilihan {$answerLetter}: " . fake()->sentence(6),
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            $this->command->info("  ✓ Quiz created for TNA '{$tna->name}': 10 questions with 4 answers each (1 correct per question).");
        }

        $this->command->info('Quiz seeder completed!');
    }
}
