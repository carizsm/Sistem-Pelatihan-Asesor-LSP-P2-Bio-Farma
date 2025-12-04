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
            // 1. Pre-test belum dikerjakan dan masih sebelum start_date
            if (!$hasPreTest && $now->lt($startDate)) {
                return true;
            }
            
            // 2. Post-test belum dikerjakan dan dalam window 30 menit setelah end_date
            if (!$hasPostTest && $now->between($endDate, $endDate->copy()->addMinutes(30))) {
                return true;
            }
            
            // 3. Feedback belum dikerjakan dan sudah setelah end_date
            if (!$hasFeedback && $now->gt($endDate)) {
                return true;
            }
            
            return false;
        });

        return view('dashboard', compact('user', 'tasks', 'registrations'));
    }
}
