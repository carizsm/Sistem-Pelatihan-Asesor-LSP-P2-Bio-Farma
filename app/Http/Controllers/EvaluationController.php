<?php

namespace App\Http\Controllers;

use App\Models\Tna;
use App\Models\FeedbackResult;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\TraineeAnswer;
use App\Models\Registration;
use App\Enums\RealizationStatus;
use App\Traits\ClearsRelatedCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    use ClearsRelatedCache;

    // ==========================================
    // EVALUASI 1 (FEEDBACK PENYELENGGARAAN)
    // ==========================================

    public function indexFeedback()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        $registrations = Cache::remember("evaluasi1_registrations_user_{$userId}", 60, function () use ($userId) {
            return Registration::where('user_id', $userId)
                ->with(['tna', 'feedbackResult'])
                ->get();
        });
        
        return view('trainee.evaluation1.index', compact('registrations'));
    }

    public function showFeedbackForm(Registration $registration)
    {
        $user = Auth::user();
        
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi1')->with('error', 'Akses ditolak.');
        }

        $registration->load('tna', 'feedbackResult');
        $tna = $registration->tna;

        // 1. CEK DATA DULU (Review Mode)
        // Jika sudah isi -> Tampilkan Review (Abaikan Status TNA)
        if ($registration->feedbackResult) {
            return view('trainee.evaluation1.form', [
                'registration' => $registration,
                'tna' => $tna,
                'feedback' => $registration->feedbackResult
            ]);
        }

        // 2. BARU CEK STATUS (Input Mode)
        // Jika belum isi -> Pastikan TNA Completed
        if ($tna->realization_status !== RealizationStatus::COMPLETED) {
            abort(403, 'Evaluasi penyelenggaraan hanya dapat diisi setelah pelatihan dinyatakan selesai.');
        }

        return view('trainee.evaluation1.form', compact('registration', 'tna'));
    }

    public function storeFeedback(Request $request, Registration $registration)
    {
        $user = Auth::user();
        
        if ($registration->user_id !== $user->id) abort(403);

        $registration->load('tna');
        $tna = $registration->tna;

        // Security Check
        if ($tna->realization_status !== RealizationStatus::COMPLETED) {
            abort(403, 'Form evaluasi tertutup.');
        }

        // Anti-Jebol (Double Submit)
        if ($registration->feedbackResult()->exists()) {
            return redirect()->route('peserta.evaluasi1')->with('error', 'Anda sudah mengisi feedback ini.');
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

        // Ini akan update Dashboard User DAN Laporan Admin sekaligus
        $this->clearUserCaches($user->id, $tna->id);

        return redirect()->route('peserta.evaluasi1')
            ->with('success', 'Feedback berhasil disimpan. Terima kasih!');
    }

    /**
     * Review feedback yang sudah diisi (Read-only mode)
     */
    public function reviewFeedback(Registration $registration)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi1')->with('error', 'Akses ditolak.');
        }

        $registration->load('tna', 'feedbackResult');
        
        // Pastikan feedback sudah ada
        if (!$registration->feedbackResult) {
            return redirect()->route('peserta.evaluasi1')
                ->with('error', 'Feedback belum diisi.');
        }

        $tna = $registration->tna;
        $feedback = $registration->feedbackResult;

        return view('trainee.evaluation1.form', compact('registration', 'tna', 'feedback'));
    }

    // ==========================================
    // EVALUASI 2 (QUIZ: PRE-TEST & POST-TEST)
    // ==========================================

    public function indexQuiz()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        $registrations = Cache::remember("evaluasi2_registrations_user_{$userId}", 60, function () use ($userId) {
            return Registration::where('user_id', $userId)
                ->with(['tna', 'quizAttempts'])
                ->get();
        });
        
        return view('trainee.evaluation2.index', compact('registrations'));
    }

    public function showQuizForm(Registration $registration, string $type)
    {
        $user = Auth::user();

        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi2')->with('error', 'Akses ditolak.');
        }
        if (!in_array($type, ['pre-test', 'post-test'])) {
            return redirect()->route('peserta.evaluasi2')->with('error', 'Tipe kuis tidak valid.');
        }

        $registration->load('tna');
        $tna = $registration->tna;
        
        // Ambil attempt yang sudah ada (Eager load untuk Review Mode)
        $attempt = QuizAttempt::where('registration_id', $registration->id)
            ->where('type', $type)
            ->with(['traineeAnswers.quizQuestion', 'traineeAnswers.quizAnswer']) 
            ->first();

        $questions = QuizQuestion::where('tna_id', $tna->id)
            ->with('quizAnswers')
            ->orderBy('question_number')
            ->get();

        // 1. CEK DATA DULU (Review Mode)
        // Jika sudah ada attempt -> Masuk Review (Abaikan Status TNA)
        if ($attempt) {
            return view('trainee.evaluation2.form', compact('registration', 'tna', 'questions', 'type', 'attempt'));
        }

        // 2. BARU CEK STATUS (Input Mode)
        if ($type === 'pre-test') {
            if ($tna->realization_status === RealizationStatus::COMPLETED) abort(403, 'Pre-test sudah ditutup.');
            if ($tna->realization_status === RealizationStatus::CANCELED) abort(403, 'Pre-test dibatalkan.');
        } elseif ($type === 'post-test') {
            // POST-TEST: Butuh status COMPLETED + masih dalam window 1 jam setelah end_date
            if ($tna->realization_status !== RealizationStatus::COMPLETED) {
                abort(403, 'Post-test belum dibuka.');
            }
            
            $endDate = \Carbon\Carbon::parse($tna->end_date);
            $now = now();
            if ($now->gt($endDate->copy()->addHour())) {
                abort(403, 'Waktu pengerjaan Post-test sudah habis (maksimal 1 jam setelah pelatihan selesai).');
            }
        }

        // 3. CEK KETERSEDIAAN SOAL (Sebelum masuk ke view)
        if ($questions->isEmpty()) {
            $typeName = $type === 'pre-test' ? 'Pre-Test' : 'Post-Test';
            return redirect()->route('peserta.evaluasi2')
                ->with('error', "Soal {$typeName} untuk pelatihan '{$tna->name}' belum tersedia. Silakan hubungi administrator.");
        }

        return view('trainee.evaluation2.form', compact('registration', 'tna', 'questions', 'type'));
    }

    public function storeQuiz(Request $request, Registration $registration, string $type)
    {
        $user = Auth::user();
        
        if ($registration->user_id !== $user->id) abort(403);

        $registration->load('tna');
        $tna = $registration->tna;

        // Security Check
        if ($type === 'pre-test') {
            if (in_array($tna->realization_status, [RealizationStatus::COMPLETED, RealizationStatus::CANCELED])) {
                abort(403, 'Waktu pengerjaan Pre-test sudah habis.');
            }
        } elseif ($type === 'post-test') {
            // POST-TEST: Butuh status COMPLETED + masih dalam window 1 jam setelah end_date
            if ($tna->realization_status !== RealizationStatus::COMPLETED) {
                abort(403, 'Post-test belum dibuka.');
            }
            
            $endDate = \Carbon\Carbon::parse($tna->end_date);
            $now = now();
            if ($now->gt($endDate->copy()->addHour())) {
                abort(403, 'Waktu pengerjaan Post-test sudah habis (maksimal 1 jam setelah pelatihan selesai).');
            }
        }

        // Anti-Jebol
        if (QuizAttempt::where('registration_id', $registration->id)->where('type', $type)->exists()) {
            return redirect()->route('peserta.evaluasi2')->with('error', "Anda sudah mengerjakan $type.");
        }

        $request->validate(['answers' => 'required|array']);

        // Transaction Logic
        $score = DB::transaction(function () use ($request, $registration, $type, $tna) {
            $answers = $request->answers;
            $questionIds = array_keys($answers);
            
            $questions = QuizQuestion::whereIn('id', $questionIds)
                ->with('quizAnswers')
                ->get()
                ->keyBy('id');

            $quizAttempt = QuizAttempt::create([
                'registration_id' => $registration->id,
                'type' => $type,
                'score' => 0,
            ]);

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

                if ($isCorrect) $correctAnswers++;
            }

            $finalScore = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
            $quizAttempt->update(['score' => $finalScore]);

            // Update kelulusan jika Post-Test
            if ($type === 'post-test') {
                $passingScore = $tna->passing_score ?? 70;
                $registration->update([
                    'status' => $finalScore >= $passingScore ? 'lulus' : 'tidak lulus',
                ]);
            }

            return $finalScore;
        });
        
        // Cache Dashboard User RESET, Cache Laporan Admin RESET.
        $this->clearUserCaches($user->id, $tna->id);

        return redirect()->route('peserta.evaluasi2')
            ->with('success', 'Kuis berhasil diselesaikan. Skor: ' . number_format($score, 2));
    }

    /**
     * Review quiz yang sudah dikerjakan (Read-only mode)
     */
    public function reviewQuiz(Registration $registration, string $type)
    {
        $user = Auth::user();

        // Authorization check
        if ($registration->user_id !== $user->id) {
            return redirect()->route('peserta.evaluasi2')->with('error', 'Akses ditolak.');
        }

        // Validate type
        if (!in_array($type, ['pre-test', 'post-test'])) {
            return redirect()->route('peserta.evaluasi2')->with('error', 'Tipe kuis tidak valid.');
        }

        $registration->load('tna');
        $tna = $registration->tna;
        
        // Ambil attempt dengan eager loading untuk review
        $attempt = QuizAttempt::where('registration_id', $registration->id)
            ->where('type', $type)
            ->with(['traineeAnswers.quizQuestion.quizAnswers', 'traineeAnswers.quizAnswer']) 
            ->first();

        // Pastikan attempt sudah ada
        if (!$attempt) {
            return redirect()->route('peserta.evaluasi2')
                ->with('error', ucfirst($type) . ' belum dikerjakan.');
        }

        // Ambil semua questions untuk keperluan navigasi
        $questions = QuizQuestion::where('tna_id', $tna->id)
            ->with('quizAnswers')
            ->orderBy('question_number')
            ->get();

        return view('trainee.evaluation2.form', compact('registration', 'tna', 'questions', 'type', 'attempt'));
    }
}