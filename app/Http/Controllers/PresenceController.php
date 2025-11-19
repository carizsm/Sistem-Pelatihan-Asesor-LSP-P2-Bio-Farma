<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Presence;
use Carbon\Carbon;

class PresenceController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $now = now();
        
        // Ambil SEMUA registrations milik user tanpa filter waktu
        $registrations = Registration::where('user_id', $user->id)
            ->with(['tna', 'presence'])
            ->get()
            ->sortByDesc(function ($reg) use ($now) {
                $start = Carbon::parse($reg->tna->start_date);
                $end = Carbon::parse($reg->tna->end_date);
                
                // Prioritas sorting:
                // 1. TNA yang sedang berlangsung (tertinggi)
                if ($now->between($start, $end)) {
                    return 1000000 + $now->diffInHours($start);
                }
                
                // 2. TNA yang akan datang (berdasarkan kedekatan)
                if ($now->lt($start)) {
                    return 100000 - $now->diffInHours($start);
                }
                
                // 3. TNA yang sudah selesai (terendah, tapi tetap tampilkan)
                return -$now->diffInDays($end);
            });
        
        return view('peserta.presensi', compact('registrations'));
    }

    public function store(Request $request)
    {
        $registration = Registration::findOrFail($request->registration_id);
        
        // Authorization: Check ownership
        if (Auth::id() !== $registration->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Eager-load TNA for time checks
        $registration->load('tna');
        $tna = $registration->tna;
        
        // Time Check: Must be within 30 minutes before start_date and before end_date
        $allowedClockInTime = Carbon::parse($tna->start_date)->subMinutes(30);
        $now = now();
        
        if ($now->lt($allowedClockInTime) || $now->gt($tna->end_date)) {
            return redirect()->back()
                ->with('error', 'Clock-in hanya dapat dilakukan 30 menit sebelum pelatihan dimulai hingga sebelum pelatihan berakhir.');
        }
        
        // Status Check: Already clocked in?
        if ($registration->presence) {
            return redirect()->back()
                ->with('error', 'Anda sudah melakukan clock-in.');
        }
        
        // Create presence record
        $registration->presence()->create([
            'clock_in' => $now
        ]);
        
        return redirect()->back()
            ->with('success', 'Clock-in berhasil!');
    }

    public function update(Presence $presence)
    {
        // Authorization: Check ownership via registration
        if (Auth::id() !== $presence->registration->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Eager-load TNA for time checks
        $tna = $presence->registration->tna;
        $now = now();
        
        // Declare clock-out allowed range
        $validClockOutStart = Carbon::parse($tna->start_date);
        $validClockOutEnd = Carbon::parse($tna->end_date)->addMinutes(30);

        if ($now->isBefore($validClockOutStart) || $now->isAfter($validClockOutEnd)) {
            return redirect()->back()
                ->with('error', 'Clock-out hanya dapat dilakukan sejak pelatihan dimulai hingga 30 menit setelah pelatihan berakhir.');
        }
        
        // Status Check: Already clocked out?
        if ($presence->clock_out) {
            return redirect()->back()
                ->with('error', 'Anda sudah melakukan clock-out.');
        }
        
        // Update with clock-out time
        $presence->update([
            'clock_out' => $now
        ]);
        
        return redirect()->back()
            ->with('success', 'Clock-out berhasil!');
    }
}
