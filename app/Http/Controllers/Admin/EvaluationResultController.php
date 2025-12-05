<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tna;
use App\Models\FeedbackResult;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EvaluationResultController extends Controller
{
    // ========== LAPORAN EVALUASI 1 (FEEDBACK) ==========
    
    public function indexFeedback()
    {
        // Cache feedback results index for 60 seconds
        $page = request()->get('page', 1);
        $tnas = Cache::remember('admin_feedback_results_index_page_' . $page, 60, function () {
            return Tna::withCount('registrations')->paginate(10);
        });
        
        return view('admin.feedback_results.index', compact('tnas'));
    }

    public function showFeedbackReport(Tna $tna)
    {
        $tnaId = $tna->id;
        
        // Cache feedback report for 60 seconds
        $feedbacks = Cache::remember("admin_feedback_report_tna_{$tnaId}", 60, function () use ($tna) {
            return FeedbackResult::whereHas('registration', function($query) use ($tna) {
                    $query->where('tna_id', $tna->id);
                })
                ->with('registration.user')
                ->get();
        });

        // Calculate averages for all 15 scores
        $averages = [];
        for ($i = 1; $i <= 15; $i++) {
            $scoreField = sprintf('score_%02d', $i);
            $averages[$scoreField] = $feedbacks->avg($scoreField);
        }
        
        // Calculate overall average
        $averages['overall'] = collect($averages)->avg();

        // Group scores by category (example: 1-5 Pengajar, 6-10 Materi, 11-15 Fasilitas)
        $categoryAverages = [
            'tujuan' => collect(range(1, 2))->map(fn($i) => $averages[sprintf('score_%02d', $i)])->avg(),
            'materi' => collect(range(3, 5))->map(fn($i) => $averages[sprintf('score_%02d', $i)])->avg(),
            'alokasi_waktu' => collect(range(6, 7))->map(fn($i) => $averages[sprintf('score_%02d', $i)])->avg(),
            'instruktur' => collect(range(8, 11))->map(fn($i) => $averages[sprintf('score_%02d', $i)])->avg(),
            'fasilitas' => collect(range(12, 13))->map(fn($i) => $averages[sprintf('score_%02d', $i)])->avg(),
            'hasil_pelatihan' => collect(range(14, 15))->map(fn($i) => $averages[sprintf('score_%02d', $i)])->avg(),
        ];

        return view('admin.feedback_results.show', compact('tna', 'feedbacks', 'averages', 'categoryAverages'));
    }

    // ========== LAPORAN EVALUASI 2 (KUIS) ==========

    public function indexQuiz()
    {
        // Cache quiz results index for 60 seconds
        $page = request()->get('page', 1);
        $tnas = Cache::remember('admin_quiz_results_index_page_' . $page, 60, function () {
            return Tna::withCount('registrations')
                        ->with(['registrations.quizAttempts'])
                        ->paginate(10);
        });

        // REVISI: Hitung statistik untuk setiap TNA
        $tnas->each(function ($tna) {
            // Ambil semua quiz attempts dari semua peserta TNA ini
            $allAttempts = $tna->registrations->flatMap(function ($registration) {
                return $registration->quizAttempts;
            });

            // Pisahkan Pre-Test dan Post-Test
            $preTestAttempts = $allAttempts->where('type', 'pre-test');
            $postTestAttempts = $allAttempts->where('type', 'post-test');
            
            // Hitung dan tambahkan properti baru ke TNA
            $tna->avg_pre_test = $preTestAttempts->avg('score');
            $tna->avg_post_test = $postTestAttempts->avg('score');
            $tna->selesai = $postTestAttempts->count();
        });
        
        return view('admin.quiz_results.index', compact('tnas'));
    }

    public function showQuizReport(Tna $tna)
    {
        $tnaId = $tna->id;
        
        // Cache pre-test attempts for 60 seconds
        $preTestAttempts = Cache::remember("admin_quiz_pretest_tna_{$tnaId}", 60, function () use ($tna) {
            return QuizAttempt::whereHas('registration', function($query) use ($tna) {
                    $query->where('tna_id', $tna->id);
                })
                ->where('type', 'pre-test')
                ->with('registration.user')
                ->get();
        });

        // Cache post-test attempts for 60 seconds
        $postTestAttempts = Cache::remember("admin_quiz_posttest_tna_{$tnaId}", 60, function () use ($tna) {
            return QuizAttempt::whereHas('registration', function($query) use ($tna) {
                    $query->where('tna_id', $tna->id);
                })
                ->where('type', 'post-test')
                ->with('registration.user')
                ->get();
        });

        $passingScore = $tna->passing_score ?? 70;

        // Calculate Pre-Test statistics
        $preTestStats = [
            'total_participants' => $preTestAttempts->count(),
            'average_score' => $preTestAttempts->avg('score'),
            'highest_score' => $preTestAttempts->max('score'),
            'lowest_score' => $preTestAttempts->min('score'),
        ];

        // Calculate Post-Test statistics
        $postTestStats = [
            'total_participants' => $postTestAttempts->count(),
            'passed' => $postTestAttempts->where('score', '>=', $passingScore)->count(),
            'failed' => $postTestAttempts->where('score', '<', $passingScore)->count(),
            'average_score' => $postTestAttempts->avg('score'),
            'highest_score' => $postTestAttempts->max('score'),
            'lowest_score' => $postTestAttempts->min('score'),
            'pass_rate' => $postTestAttempts->count() > 0 
                ? ($postTestAttempts->where('score', '>=', $passingScore)->count() / $postTestAttempts->count()) * 100 
                : 0,
        ];

        return view('admin.quiz_results.show', compact(
            'tna', 
            'preTestAttempts', 
            'postTestAttempts', 
            'preTestStats', 
            'postTestStats',
            'passingScore'
        ));
    }
}