<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Enums\RealizationStatus;
use App\Models\Registration;
use App\Models\User;

class DashboardController extends Controller {
    public function index()
    {
        // Eager-load position and unit untuk user yang sedang login
        /** @var User $user */
        $user = Auth::user();
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
        $endDate = \Carbon\Carbon::parse($tna->end_date);
        
        // Cek Status Pengerjaan
        $hasFeedback = $registration->feedbackResult;
        $hasPreTest = $registration->quizAttempts->where('type', 'pre-test')->isNotEmpty();
        $hasPostTest = $registration->quizAttempts->where('type', 'post-test')->isNotEmpty();
        $hasCheckIn = $registration->presence && $registration->presence->check_in;

        if (!$hasPreTest && 
            in_array($tna->realization_status, [RealizationStatus::OPEN, RealizationStatus::RUNNING]) && 
            $hasCheckIn
        ) {
            return true;
        }
        
        if (!$hasPostTest && 
            $tna->realization_status === RealizationStatus::COMPLETED &&
            $now->lte($endDate->copy()->addDay())) {
            return true;
        }
        
        if (!$hasFeedback && $tna->realization_status === RealizationStatus::COMPLETED) {
            return true;
        }
        
        return false;
    });

        return view('dashboard', compact('user', 'tasks', 'registrations'));
    }
}
