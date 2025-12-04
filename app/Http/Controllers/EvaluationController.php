<?php

namespace App\Http\Controllers;

use App\Models\Tna;
use App\Models\FeedbackResult;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\TraineeAnswer;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class EvaluationController extends Controller
{
    // ========== EVALUASI 1 (FEEDBACK) ==========
    
    public function indexFeedback()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        // Cache feedback evaluations for 60 seconds
        $registrations = Cache::remember("evaluasi1_registrations_user_{$userId}", 60, function () use ($userId) {
            return Registration::where('user_id', $userId)
                ->with(['tna', 'feedbackResult'])
                ->get();
        });
        
        return view('peserta.evaluasi1', compact('registrations'));
    }

    public function showFeedbackForm(Registration $registration)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi1')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        // Eager load tna and feedbackResult
        $registration->load('tna', 'feedbackResult');
        $tna = $registration->tna;
        $now = Carbon::now();

        // PRODUCTION: Validasi waktu aktif
        if ($tna->end_date && $now->lte(Carbon::parse($tna->end_date))) {
            return redirect()->route('peserta.evaluasi1')
                ->with('error', 'Feedback hanya bisa diisi setelah pelatihan selesai pada ' . Carbon::parse($tna->end_date)->format('d M Y H:i'));
        }
        
        // TESTING: Comment code di atas untuk testing

        // Check if already submitted
        if ($registration->feedbackResult) {
            return redirect()->route('peserta.evaluasi1')
                ->with('info', 'Anda sudah mengisi feedback untuk TNA ini.');
        }

        return view('peserta.feedback', compact('registration', 'tna'));
    }

    public function storeFeedback(Request $request, Registration $registration)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi1')
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
        
        // Clear related caches
        $tnaId = $registration->tna_id;
        Cache::forget("evaluasi1_registrations_user_{$user->id}");
        Cache::forget("dashboard_registrations_user_{$user->id}");
        Cache::forget('admin_feedback_results_index');
        Cache::forget("admin_feedback_report_tna_{$tnaId}");

        return redirect()->route('peserta.evaluasi1')
            ->with('success', 'Feedback berhasil disimpan. Terima kasih!');
    }

    public function reviewFeedback(Registration $registration)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi1')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        // Eager load tna and feedbackResult
        $registration->load('tna', 'feedbackResult');
        
        // Check if feedback exists
        $feedback = $registration->feedbackResult;
        if (!$feedback) {
            return redirect()->route('peserta.evaluasi1')
                ->with('error', 'Anda belum mengisi feedback untuk TNA ini.');
        }

        $tna = $registration->tna;
        
        // Gunakan view yang sama, tapi kirim $feedback untuk mode review
        return view('peserta.feedback', compact('registration', 'tna', 'feedback'));
    }

    // ========== EVALUASI 2 (KUIS) ==========

    public function indexQuiz()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        // Cache quiz evaluations for 60 seconds
        $registrations = Cache::remember("evaluasi2_registrations_user_{$userId}", 60, function () use ($userId) {
            return Registration::where('user_id', $userId)
                ->with(['tna', 'quizAttempts'])
                ->get();
        });
        
        return view('peserta.evaluasi2', compact('registrations'));
    }

    public function showQuizForm(Registration $registration, string $type)
    {
        // HAPUS: Debug dd() sudah tidak diperlukan
        // dd([
        //     'registration_id' => $registration->id,
        //     'type' => $type,
        //     'user_id' => auth()->id(),
        //     'message' => 'Method showQuizForm dipanggil!'
        // ]);

        $user = Auth::user();
        $now = Carbon::now();

        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        // Validate type
        if (!in_array($type, ['pre-test', 'post-test'])) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', 'Tipe kuis tidak valid.');
        }

        // Eager load tna
        $registration->load('tna');
        $tna = $registration->tna;
        $startDate = Carbon::parse($tna->start_date);
        $endDate = Carbon::parse($tna->end_date);

        // Apply time logic based on quiz type
        if ($type === 'pre-test') {
            // Pre-test only available before training starts
            if ($now->gte($startDate)) {
                return redirect()->route('peserta.evaluasi2')
                    ->with('error', 'Pre-Test hanya tersedia sebelum pelatihan dimulai.');
            }
        } elseif ($type === 'post-test') {
            // Post-test only available in 30-minute window after training ends
            $postTestStart = $endDate;
            $postTestEnd = $endDate->copy()->addMinutes(30);

            if ($now->lt($postTestStart) || $now->gt($postTestEnd)) {
                return redirect()->route('peserta.evaluasi2')
                    ->with('error', 'Post-Test hanya tersedia selama 30 menit setelah pelatihan berakhir.');
            }
        }

        // Check if already attempted
        $existingAttempt = QuizAttempt::where('registration_id', $registration->id)
            ->where('type', $type)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('peserta.evaluasi2')
                ->with('info', 'Anda sudah mengerjakan ' . $type . ' untuk TNA ini.');
        }

        $questions = QuizQuestion::where('tna_id', $tna->id)
            ->with('quizAnswers')
            ->orderBy('question_number')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', 'Belum ada soal kuis tersedia.');
        }

        return view('peserta.quiz', compact('registration', 'tna', 'questions', 'type'));
    }

    public function storeQuiz(Request $request, Registration $registration, string $type)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        $request->validate([
            'answers' => 'required|array',
        ]);

        $answers = $request->answers;
        
        // Eager load tna
        $registration->load('tna');
        $tna = $registration->tna;

        // FIXED: Load all questions with answers BEFORE the loop to prevent N+1
        $questionIds = array_keys($answers);
        $questions = QuizQuestion::whereIn('id', $questionIds)
            ->with('quizAnswers')
            ->get()
            ->keyBy('id');

        // ⚠️ PERBAIKAN DI SINI! Tambahkan registration_id
        $quizAttempt = QuizAttempt::create([
            'registration_id' => $registration->id,  // ← INI YANG DITAMBAHKAN!
            'type' => $type,
            'score' => null,
        ]);

        // Calculate score
        $totalQuestions = count($answers);
        $correctAnswers = 0;

        foreach ($answers as $questionId => $answerId) {
            $question = $questions->get($questionId);
            if (!$question) continue;
            
            $answer = $question->quizAnswers->firstWhere('id', $answerId);
            
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
        
        // Clear related caches
        $tnaId = $registration->tna_id;
        Cache::forget("evaluasi2_registrations_user_{$user->id}");
        Cache::forget("dashboard_registrations_user_{$user->id}");
        Cache::forget('admin_quiz_results_index');
        Cache::forget("admin_quiz_pretest_tna_{$tnaId}");
        Cache::forget("admin_quiz_posttest_tna_{$tnaId}");

        return redirect()->route('peserta.evaluasi2')
            ->with('success', 'Kuis berhasil diselesaikan. Skor Anda: ' . number_format($score, 2) . '%');
    }

    public function reviewQuiz(Registration $registration, string $type)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', 'Anda tidak memiliki akses ke registrasi ini.');
        }

        // Validate type
        if (!in_array($type, ['pre-test', 'post-test'])) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', 'Tipe kuis tidak valid.');
        }

        // Eager load tna
        $registration->load('tna');
        
        // Get quiz attempt
        $attempt = QuizAttempt::where('registration_id', $registration->id)
            ->where('type', $type)
            ->with(['traineeAnswers.quizQuestion.quizAnswers', 'traineeAnswers.quizAnswer'])
            ->first();

        if (!$attempt) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', 'Anda belum mengerjakan ' . $type . ' untuk TNA ini.');
        }

        $tna = $registration->tna;
        
        // Gunakan view yang sama (quiz.blade.php), tapi kirim $attempt untuk mode review
        return view('peserta.quiz', compact('registration', 'tna', 'attempt', 'type'));
    }
}
