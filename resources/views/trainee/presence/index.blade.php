@extends('layouts.trainee')

@section('title', 'Presensi')
@section('page_title', 'Presensi Pelatihan Asesor LSP')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Presensi Pelatihan Asesor LSP</h2> 
    <p class="text-sm text-gray-600 mb-4">Presensi Peserta</p> 

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded-lg shadow-sm">
            <thead>
                <tr class="bg-[#F26E22] text-white">
                    <th class="py-3 px-4 text-left">Nama Pelatihan</th>
                    <th class="py-3 px-4 text-left">Waktu Mulai</th>
                    <th class="py-3 px-4 text-left">Check-In</th>
                    <th class="py-3 px-4 text-left">Check-Out</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $registration)
                    @php
                        $tna = $registration->tna;
                        $now = \Carbon\Carbon::now();
                        $startDate = \Carbon\Carbon::parse($tna->start_date);
                        $endDate = \Carbon\Carbon::parse($tna->end_date);
                        
                        // Window waktu toleransi
                        $clockInStart = $startDate->copy()->subMinutes(30);
                        $clockOutEnd = $endDate->copy()->addMinutes(30);
                        $presence = $registration->presence;
                        
                        // --- SECURITY STATUS CHECK (LOGIC BARU) ---
                        // Import Enum atau pakai Full Namespace
                        $status = $tna->realization_status;
                        $isRunning = $status === \App\Enums\RealizationStatus::RUNNING;
                        $isCompleted = $status === \App\Enums\RealizationStatus::COMPLETED;
                        // ------------------------------------------

                        // LOGIC CLOCK-IN:
                        // 1. Belum absen
                        // 2. Waktu masuk akal (H-30 s/d Selesai)
                        // 3. WAJIB RUNNING (Admin sudah klik Start)
                        $canClockIn = !$presence && 
                                      $now->between($clockInStart, $endDate) && 
                                      $isRunning;
                        
                        // Cek terlambat Clock-In
                        // Terlambat jika: Waktu habis ATAU (Status sudah selesai TAPI belum absen)
                        $isTooLateForClockIn = !$presence && ($now->gt($endDate) || $isCompleted);
                        
                        // LOGIC CLOCK-OUT:
                        // 1. Sudah Clock-In
                        // 2. Belum Clock-Out
                        // 3. Masih dalam range waktu pulang
                        // 4. Status boleh RUNNING atau COMPLETED (Jaga-jaga admin close duluan sebelum peserta pulang)
                        $canClockOut = $presence && $presence->clock_in && !$presence->clock_out &&
                                       $now->between($startDate, $clockOutEnd) &&
                                       ($isRunning || $isCompleted);
                        
                        // Cek terlambat Clock-Out
                        $isTooLateForClockOut = $presence && $presence->clock_in && !$presence->clock_out &&
                                                 $now->gt($clockOutEnd);
                    @endphp
                    <tr class="border-b {{ $now->between($startDate, $endDate) ? 'bg-yellow-50' : '' }}">
                        <td class="py-3 px-4">
                            <div class="flex flex-col">
                                <span class="font-semibold">{{ $tna->name }}</span>
                                @if($now->between($startDate, $endDate))
                                    <span class="text-xs text-green-600 font-semibold">● Sedang Berlangsung</span>
                                @elseif($now->lt($startDate))
                                    <span class="text-xs text-blue-600">○ Akan Datang</span>
                                @else
                                    <span class="text-xs text-gray-400">○ Selesai</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col">
                                <span>{{ $startDate->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $startDate->format('H:i') }} - {{ $endDate->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if($presence && $presence->clock_in)
                                <span class="text-green-600 font-semibold">
                                    ✓ {{ \Carbon\Carbon::parse($presence->clock_in)->timezone('Asia/Jakarta')->format('H:i') }}
                                </span>
                            @elseif($isTooLateForClockIn)
                                <span class="text-red-600 font-semibold">
                                    ✕ Terlambat
                                </span>
                            @else
                                <span class="text-gray-400">Belum absen</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($presence && $presence->clock_out)
                                <span class="text-green-600 font-semibold">
                                    ✓ {{ \Carbon\Carbon::parse($presence->clock_out)->timezone('Asia/Jakarta')->format('H:i') }}
                                </span>
                            @elseif($isTooLateForClockOut)
                                <span class="text-red-600 font-semibold">
                                    ✕ Terlambat
                                </span>
                            @else
                                <span class="text-gray-400">Belum absen</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            {{-- Jika sudah clock-out --}}
                            @if($presence && $presence->clock_out)
                                <span class="px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-lg">
                                    ✓ Selesai
                                </span>

                            {{-- Jika sudah terlambat untuk clock-out --}}
                            @elseif($isTooLateForClockOut)
                                <span class="px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-lg">
                                    ✕ Terlambat Clock-Out
                                </span>

                            {{-- Jika clock-in sudah, dan bisa clock-out --}}
                            @elseif($canClockOut)
                                <form method="POST" action="{{ route('presence.update', $presence) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-4 py-2 bg-[#F26E22] text-white rounded-lg hover:bg-[#e65c0d] transition">
                                        Clock-Out
                                    </button>
                                </form>

                            {{-- Jika sudah clock-in tapi belum waktunya clock-out --}}
                            @elseif($presence && $presence->clock_in && !$canClockOut && !$isTooLateForClockOut)
                                <span class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg">
                                    Menunggu Clock-Out
                                </span>

                            {{-- Jika terlambat untuk clock-in (sudah melewati end_date) --}}
                            @elseif($isTooLateForClockIn)
                                <span class="px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-lg">
                                    ✕ Terlambat Clock-In
                                </span>

                            {{-- Jika belum clock-in dan boleh clock-in --}}
                            @elseif($canClockIn)
                                <form method="POST" action="{{ route('presence.store') }}">
                                    @csrf
                                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                                    <button type="submit" class="px-4 py-2 bg-[#F26E22] text-white rounded-lg hover:bg-[#e65c0d] transition">
                                        Clock-In
                                    </button>
                                </form>

                            {{-- Tidak tersedia (belum waktunya clock-in) --}}
                            @else
                                <span class="px-4 py-2 bg-gray-100 text-gray-500 text-sm rounded-lg">
                                    Tidak Tersedia
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="border-b">
                        <td colspan="5" class="py-6 px-4 text-center text-gray-500">
                            Tidak ada pelatihan yang tersedia untuk presensi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
