<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tna;
use App\Models\Registration;
use App\Enums\UserRole;
use App\Enums\RealizationStatus;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache dashboard statistics for 60 seconds
        $stats = Cache::remember('admin_dashboard_stats', 60, function () {
            // 1. Total Participants (Trainees)
            $totalParticipants = User::where('role', UserRole::TRAINEE)->count();
            
            // 2. Total TNAs
            $totalTnas = Tna::count();
            
            // 3. Active/Scheduled TNAs (efficient database query)
            $activeTnas = Tna::where('realization_status', '!=', RealizationStatus::TIDAK_TEREALISASI)
                ->where('end_date', '>', now())
                ->count();
            
            // 4. Total Registrations
            $totalRegistrations = Registration::count();
            
            // Compile statistics
            return [
                'total_participants' => $totalParticipants,
                'total_tnas' => $totalTnas,
                'active_tnas' => $activeTnas,
                'total_registrations' => $totalRegistrations,
            ];
        });
        
        return view('admin.dashboard', compact('stats'));
    }
}
