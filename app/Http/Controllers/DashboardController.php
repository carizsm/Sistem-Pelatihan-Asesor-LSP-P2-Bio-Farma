<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Registration;

class DashboardController extends Controller {
    public function index()
    {
        $user = Auth::user();

        // Eager-load all registration-related data to avoid N+1
        $registrations = Registration::where('user_id', $user->id)
            ->with([
                'tna',
                'quizAttempts',
                'feedbackResult',
                'presence',
            ])
            ->get();

        // Partition into tasks (active) and history (completed/cancelled)
        $partitioned = $registrations->partition(function ($registration) {
            $status = $registration->tna->status;
            return in_array($status, ['Dijadwalkan', 'Sedang Berlangsung']);
        });

        $tasks = $partitioned[0];
        $history = $partitioned[1];

        return view('dashboard', compact('user', 'tasks', 'history'));
    }
}
