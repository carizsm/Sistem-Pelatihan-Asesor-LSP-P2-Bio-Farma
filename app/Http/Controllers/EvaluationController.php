<?php

namespace App\Http\Controllers;

use App\Models\Tna;
use App\Models\FeedbackResult;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\TraineeAnswer;
use App\Models\Registration;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EvaluationController extends Controller
{
    // ========== EVALUASI 1 (FEEDBACK) ==========
    
    public function indexFeedback()
    {
        $user = auth()->user();
        
        $registrations = Registration::where('user_id', $user->id)
            ->with(['tna', 'feedbackResult'])
            ->get();
        
        return view('peserta.evaluasi1', compact('registrations'));
    }

    public function showFeedbackForm(Registration $registration)
    {
        $user = auth()->user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('evaluasi1.index')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        $tna = $registration->tna;
        $now = Carbon::now();

        // Check if feedback can be accessed (only after training ends)
        if ($tna->end_date && $now->lte(Carbon::parse($tna->end_date))) {
            return redirect()->route('evaluasi1.index')
                ->with('error', 'Feedback hanya bisa diisi setelah pelatihan selesai.');
        }

        // Check if already submitted
        if ($registration->feedbackResult) {
            return redirect()->route('evaluasi1.index')
                ->with('info', 'Anda sudah mengisi feedback untuk TNA ini.');
        }

        return view('peserta.feedback_form', compact('registration', 'tna'));
    }

    public function storeFeedback(Request $request, Registration $registration)
    {
        $user = auth()->user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('evaluasi1.index')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        $validated = $request->validate([
            'score_01' => 'required|integer|min:1|max:4',
            'score_02' => 'required|integer|min:1|max:4',
            'score_03' => 'required|integer|min:1|max:4',
            'score_04' => 'required|integer|min:1|max:4',
            'score_05' => 'required|integer|min:1|max:4',
            'score_06' => 'required|integer|min:1|max:4',
            'score_07' => 'required|integer|min:1|max:4',
            'score_08' => 'required|integer|min:1|max:4',
            'score_09' => 'required|integer|min:1|max:4',
            'score_10' => 'required|integer|min:1|max:4',
            'score_11' => 'required|integer|min:1|max:4',
            'score_12' => 'required|integer|min:1|max:4',
            'score_13' => 'required|integer|min:1|max:4',
            'score_14' => 'required|integer|min:1|max:4',
            'score_15' => 'required|integer|min:1|max:4',
        ]);

        FeedbackResult::create([
            'registration_id' => $registration->id,
            'feedback_date' => now(),
            'score_01' => $validated['score_01'],
            'score_02' => $validated['score_02'],
            'score_03' => $validated['score_03'],
            'score_04' => $validated['score_04'],
            'score_05' => $validated['score_05'],
            'score_06' => $validated['score_06'],
            'score_07' => $validated['score_07'],
            'score_08' => $validated['score_08'],
            'score_09' => $validated['score_09'],
            'score_10' => $validated['score_10'],
            'score_11' => $validated['score_11'],
            'score_12' => $validated['score_12'],
            'score_13' => $validated['score_13'],
            'score_14' => $validated['score_14'],
            'score_15' => $validated['score_15'],
        ]);

        return redirect()->route('evaluasi1.index')
            ->with('success', 'Feedback berhasil disimpan. Terima kasih!');
    }

    // ========== EVALUASI 2 (KUIS) ==========

    public function indexQuiz()
    {
        $user = auth()->user();
        
        $registrations = Registration::where('user_id', $user->id)
            ->with(['tna', 'quizAttempts'])
            ->get();
        
        return view('peserta.evaluasi2', compact('registrations'));
    }

    public function showQuizForm(Registration $registration, string $type)
    {
        $user = auth()->user();
        $now = Carbon::now();

        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('evaluasi2.index')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        // Validate type
        if (!in_array($type, ['pre-test', 'post-test'])) {
            return redirect()->route('evaluasi2.index')
                ->with('error', 'Tipe kuis tidak valid.');
        }

        $tna = $registration->tna;
        $startDate = Carbon::parse($tna->start_date);
        $endDate = Carbon::parse($tna->end_date);

        // Apply time logic based on quiz type
        if ($type === 'pre-test') {
            // Pre-test only available before training starts
            if ($now->gte($startDate)) {
                return redirect()->route('evaluasi2.index')
                    ->with('error', 'Pre-Test hanya tersedia sebelum pelatihan dimulai.');
            }
        } elseif ($type === 'post-test') {
            // Post-test only available in 30-minute window after training ends
            $postTestStart = $endDate;
            $postTestEnd = $endDate->copy()->addMinutes(30);

            if ($now->lt($postTestStart) || $now->gt($postTestEnd)) {
                return redirect()->route('evaluasi2.index')
                    ->with('error', 'Post-Test hanya tersedia selama 30 menit setelah pelatihan berakhir.');
            }
        }

        // Check if already attempted
        $existingAttempt = QuizAttempt::where('registration_id', $registration->id)
            ->where('type', $type)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('evaluasi2.index')
                ->with('info', 'Anda sudah mengerjakan ' . $type . ' untuk TNA ini.');
        }

        $questions = QuizQuestion::where('tna_id', $tna->id)
            ->with('answers')
            ->orderBy('question_number')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('evaluasi2.index')
                ->with('error', 'Belum ada soal kuis tersedia.');
        }

        return view('peserta.quiz_form', compact('registration', 'tna', 'questions', 'type'));
    }

    public function storeQuiz(Request $request, Registration $registration, string $type)
    {
        $user = auth()->user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('evaluasi2.index')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        $request->validate([
            'answers' => 'required|array',
        ]);

        $answers = $request->answers;
        $tna = $registration->tna;

        // Create quiz attempt first
        $quizAttempt = QuizAttempt::create([
            'registration_id' => $registration->id,
            'type' => $type,
            'score' => null,
        ]);

        // Calculate score
        $totalQuestions = count($answers);
        $correctAnswers = 0;

        foreach ($answers as $questionId => $answerId) {
            $question = QuizQuestion::find($questionId);
            $answer = $question->answers()->where('id', $answerId)->first();
            
            $isCorrect = $answer && $answer->is_correct;
            
            TraineeAnswer::create([
                'quiz_attempt_id' => $quizAttempt->id,
                'quiz_question_id' => $questionId,
                'quiz_answer_id' => $answerId,
            ]);

            if ($isCorrect) {
                $correctAnswers++;
            }
        }

        $score = ($correctAnswers / $totalQuestions) * 100;

        // Update quiz attempt with score
        $quizAttempt->update([
            'score' => $score,
        ]);

        // Update registration status if post-test
        if ($type === 'post-test') {
            $passingScore = $tna->passing_score ?? 70;
            $registration->update([
                'status' => $score >= $passingScore ? 'lulus' : 'tidak lulus',
            ]);
        }

        return redirect()->route('evaluasi2.index')
            ->with('success', 'Kuis berhasil diselesaikan. Skor Anda: ' . number_format($score, 2) . '%');
    }
}
