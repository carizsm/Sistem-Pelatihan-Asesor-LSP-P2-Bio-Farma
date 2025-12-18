<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\QuizAttempt;
use App\Models\TraineeAnswer;
use App\Enums\QuizAttemptType;
use Illuminate\Database\Seeder;

class QuizAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan eager load quizAnswers biar query gak berat (N+1 Problem)
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

            $totalQuestions = $tna->quizQuestions->count();

            // Pre-Test
            $preTest = QuizAttempt::factory()->create([
                'registration_id' => $registration->id,
                'type' => QuizAttemptType::PRE_TEST,
                'score' => 0, // Set 0 dulu, nanti dihitung ulang
            ]);

            $correctCount = 0;

            foreach ($tna->quizQuestions as $question) {
                $isCorrect = rand(1, 100) <= 40; 
                
                // Periksa Jawaban
                $correctAnswer = $question->quizAnswers->where('is_correct', true)->first();
                $wrongAnswer = $question->quizAnswers->where('is_correct', false)->random(); // Ambil acak jawaban salah

                // Tentukan Jawaban User
                $selectedAnswer = $isCorrect ? $correctAnswer : ($wrongAnswer ?? $question->quizAnswers->random());

                TraineeAnswer::factory()->create([
                    'quiz_attempt_id' => $preTest->id,
                    'quiz_question_id' => $question->id,
                    'quiz_answer_id' => $selectedAnswer->id,
                ]);

                if ($selectedAnswer->is_correct) $correctCount++;
            }

            // Hitung Skor Asli
            $realScore = ($totalQuestions > 0) ? round(($correctCount / $totalQuestions) * 100) : 0;
            $preTest->update(['score' => $realScore]);
            
            $attemptCount++;


            // Post-Test
            if (rand(1, 100) <= 80) {
                $postTest = QuizAttempt::factory()->postTest()->create([
                    'registration_id' => $registration->id,
                    'type' => QuizAttemptType::POST_TEST,
                    'score' => 0,
                ]);

                $correctCountPost = 0;

                foreach ($tna->quizQuestions as $question) {
                    $isCorrect = rand(1, 100) <= 85; 

                    $correctAnswer = $question->quizAnswers->where('is_correct', true)->first();
                    $wrongAnswer = $question->quizAnswers->where('is_correct', false)->random();

                    $selectedAnswer = $isCorrect ? $correctAnswer : ($wrongAnswer ?? $question->quizAnswers->random());

                    TraineeAnswer::factory()->create([
                        'quiz_attempt_id' => $postTest->id,
                        'quiz_question_id' => $question->id,
                        'quiz_answer_id' => $selectedAnswer->id,
                    ]);

                    if ($selectedAnswer->is_correct) $correctCountPost++;
                }

                $realScorePost = ($totalQuestions > 0) ? round(($correctCountPost / $totalQuestions) * 100) : 0;
                $postTest->update(['score' => $realScorePost]);
                
                $passingScore = $tna->passing_score ?? 70;
                $registration->update([
                    'status' => $realScorePost >= $passingScore ? 'lulus' : 'tidak lulus'
                ]);

                $attemptCount++;
            }
        }

        $this->command->info("QuizAttempt seeder completed: {$attemptCount} attempts created with REAL calculated scores.");
    }
}