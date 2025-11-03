<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tna;
use App\Models\FeedbackResult;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class EvaluationResultController extends Controller
{
    // ========== LAPORAN EVALUASI 1 (FEEDBACK) ==========
    
    public function indexFeedback()
    {
        $tnas = Tna::withCount('registrations')->get();
        
        return view('admin.results_feedback.index', compact('tnas'));
    }

    public function showFeedbackReport(Tna $tna)
    {
        $feedbacks = FeedbackResult::whereHas('registration', function($query) use ($tna) {
                $query->where('tna_id', $tna->id);
            })
            ->with('registration.user')
            ->get();

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

        return view('admin.results_feedback.show', compact('tna', 'feedbacks', 'averages', 'categoryAverages'));
    }

    // ========== LAPORAN EVALUASI 2 (KUIS) ==========

    public function indexQuiz()
    {
        $tnas = Tna::withCount('registrations')->get();
        
        return view('admin.results_quiz.index', compact('tnas'));
    }

    public function showQuizReport(Tna $tna)
    {
        // Get Pre-Test attempts
        $preTestAttempts = QuizAttempt::whereHas('registration', function($query) use ($tna) {
                $query->where('tna_id', $tna->id);
            })
            ->where('type', 'pre-test')
            ->with('registration.user')
            ->get();

        // Get Post-Test attempts
        $postTestAttempts = QuizAttempt::whereHas('registration', function($query) use ($tna) {
                $query->where('tna_id', $tna->id);
            })
            ->where('type', 'post-test')
            ->with('registration.user')
            ->get();

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

        return view('admin.results_quiz.show', compact(
            'tna', 
            'preTestAttempts', 
            'postTestAttempts', 
            'preTestStats', 
            'postTestStats',
            'passingScore'
        ));
    }
}
