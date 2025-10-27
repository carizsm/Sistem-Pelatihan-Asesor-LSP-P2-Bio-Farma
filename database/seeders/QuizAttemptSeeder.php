<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\QuizAttempt;
use App\Models\TraineeAnswer;
use App\Models\QuizQuestion;
use App\Enums\QuizAttemptType;
use Illuminate\Database\Seeder;

class QuizAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registrations = Registration::with('tna.quizQuestions.quizAnswers')->get();

        if ($registrations->isEmpty()) {
            $this->command->warn('No registrations found. Please run RegistrationSeeder first.');
            return;
        }

        $attemptCount = 0;

        foreach ($registrations as $registration) {
            $tna = $registration->tna;

            if (!$tna || $tna->quizQuestions->isEmpty()) {
                continue;
            }

            // 1. Buat Pre-Test
            $preTest = QuizAttempt::factory()->create([
                'registration_id' => $registration->id,
                'type' => QuizAttemptType::PRE_TEST,
                'score' => rand(40, 75), // Score pre-test biasanya lebih rendah
            ]);

            // Jawab semua soal untuk Pre-Test
            foreach ($tna->quizQuestions as $question) {
                $randomAnswer = $question->quizAnswers->random();
                
                TraineeAnswer::factory()->create([
                    'quiz_attempt_id' => $preTest->id,
                    'quiz_question_id' => $question->id,
                    'quiz_answer_id' => $randomAnswer->id,
                ]);
            }

            $attemptCount++;

            // 2. Buat Post-Test (80% chance ada post-test)
            if (rand(1, 100) <= 80) {
                $postTest = QuizAttempt::factory()->postTest()->create([
                    'registration_id' => $registration->id,
                    'type' => QuizAttemptType::POST_TEST,
                    'score' => rand(70, 100), // Score post-test biasanya lebih tinggi
                ]);

                // Jawab semua soal untuk Post-Test
                foreach ($tna->quizQuestions as $question) {
                    $randomAnswer = $question->quizAnswers->random();
                    
                    TraineeAnswer::factory()->create([
                        'quiz_attempt_id' => $postTest->id,
                        'quiz_question_id' => $question->id,
                        'quiz_answer_id' => $randomAnswer->id,
                    ]);
                }

                $attemptCount++;
            }
        }

        $this->command->info("QuizAttempt seeder completed: {$attemptCount} attempts created (Pre-Test & Post-Test).");
    }
}
