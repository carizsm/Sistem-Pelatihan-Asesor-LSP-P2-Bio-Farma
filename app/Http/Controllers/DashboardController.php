<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Registration;
use App\Models\User;

class DashboardController extends Controller {
    public function index()
    {
        // Eager-load position and unit untuk user yang sedang login
        /** @var User $user */
        $user = Auth::user();
        $user->load(['position', 'unit']);
        $userId = $user->id;

        // Cache registrations data for 60 seconds
        $registrations = Cache::remember("dashboard_registrations_user_{$userId}", 60, function () use ($userId) {
            return Registration::where('user_id', $userId)
                ->with([
                    'tna',
                    'quizAttempts',
                    'feedbackResult',
                    'presence',
                ])
                ->get();
        });

        // Filter tasks yang memiliki pekerjaan yang belum selesai
        $tasks = $registrations->filter(function ($registration) {
            $tna = $registration->tna;
            $now = now();
            $startDate = \Carbon\Carbon::parse($tna->start_date);
            $endDate = \Carbon\Carbon::parse($tna->end_date);
            
            $hasFeedback = $registration->feedbackResult;
            $hasPreTest = $registration->quizAttempts->where('type', 'pre-test')->isNotEmpty();
            $hasPostTest = $registration->quizAttempts->where('type', 'post-test')->isNotEmpty();
            
            // Ada task jika:
            // 1. Pre-test belum dikerjakan dan status TNA OPEN atau RUNNING
            if (!$hasPreTest && in_array($tna->realization_status, [\App\Enums\RealizationStatus::OPEN, \App\Enums\RealizationStatus::RUNNING])) {
                return true;
            }
            
            // 2. Post-test belum dikerjakan, status TNA COMPLETED, dan maksimal 1 jam setelah end_date
            if (!$hasPostTest && 
                $tna->realization_status === \App\Enums\RealizationStatus::COMPLETED &&
                $now->lte($endDate->copy()->addHour())) {
                return true;
            }
            
            // 3. Feedback belum dikerjakan dan status TNA COMPLETED
            if (!$hasFeedback && $tna->realization_status === \App\Enums\RealizationStatus::COMPLETED) {
                return true;
            }
            
            return false;
        });

        return view('dashboard', compact('user', 'tasks', 'registrations'));
    }
}
