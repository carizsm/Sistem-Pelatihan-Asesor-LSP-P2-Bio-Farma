@extends('layouts.trainee')

@section('title', 'Evaluasi 2')
@section('page_title', 'Evaluasi 2')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Daftar Evaluasi 2</h2> 
    <p class="text-sm text-gray-600 mb-4">Daftar tugas yang tersedia</p>
    
    {{-- Card tugas --}} 
    <div class="bg-[#F3F3F3] rounded-xl shadow-sm p-6 space-y-4"> 
        @forelse($registrations as $registration)
            @php
                $tna = $registration->tna;
                
                // Cek Attempt (Sudah pernah kerjakan?)
                $preTestAttempt = $registration->quizAttempts->where('type', 'pre-test')->first();
                $postTestAttempt = $registration->quizAttempts->where('type', 'post-test')->first();
                
                // --- SECURITY STATUS CHECK (LOGIC BARU) ---
                $status = $tna->realization_status;
                $now = now();
                $endDate = \Carbon\Carbon::parse($tna->end_date);
                
                // PRE-TEST: Buka saat Persiapan (OPEN) atau Sedang Jalan (RUNNING)
                // Tutup kalau sudah dikerjakan ATAU status COMPLETED/CANCELED
                $isPreTestOpen = !$preTestAttempt && in_array($status, [
                    \App\Enums\RealizationStatus::OPEN, 
                    \App\Enums\RealizationStatus::RUNNING
                ]);
                
                // POST-TEST: Buka HANYA saat:
                // 1. Status COMPLETED
                // 2. Belum dikerjakan
                // 3. Masih dalam window 1 jam setelah end_date
                $isPostTestOpen = !$postTestAttempt && 
                                 $status === \App\Enums\RealizationStatus::COMPLETED &&
                                 $now->lte($endDate->copy()->addHour());
                // ------------------------------------------
            @endphp

            {{-- Pre-Test Card --}}
            <div class="card-content bg-white rounded-lg shadow-sm px-5 py-3 flex justify-between items-center border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-[#E6F4F1] rounded-md shrink-0">
                        <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-10 h-10" alt="Pre-Test">
                    </div>
                    <div class="card-text flex flex-col justify-center">
                        <p class="text-sm text-gray-500 leading-none">Pre-Test</p>
                        <h3 class="font-semibold text-gray-900 text-base leading-tight mt-1">{{ $tna->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">
                            Periode: {{ \Carbon\Carbon::parse($tna->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tna->end_date)->format('d M Y') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Status: 
                            @if($isPreTestOpen) <span class="text-green-600 font-semibold">Tersedia</span>
                            @else <span class="text-red-500">Ditutup</span> @endif
                        </p>
                    </div>
                </div>

                @if($preTestAttempt)
                    <a href="{{ route('evaluasi2.review', [$registration, 'pre-test']) }}" 
                       class="px-4 py-2 bg-[#17A2B8] text-white text-sm font-semibold rounded-md hover:bg-[#138496] transition-all duration-200 whitespace-nowrap min-w-[140px] text-center leading-none">
                        Review
                    </a>
                @elseif(!$isPreTestOpen)
                    <span class="px-4 py-2 bg-gray-100 text-gray-500 text-sm font-semibold rounded-md whitespace-nowrap min-w-[140px] text-center leading-none">
                        @if($status === \App\Enums\RealizationStatus::COMPLETED)
                            Sudah Berakhir
                        @else
                            Tidak Tersedia
                        @endif
                    </span>
                @else
                    <a href="{{ route('evaluasi2.quiz.form', [$registration, 'pre-test']) }}" 
                       class="px-4 py-2 bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm font-semibold rounded-md transition whitespace-nowrap min-w-[140px] text-center leading-none">
                       Kerjakan
                    </a>
                @endif
            </div>

            {{-- Post-Test Card --}}
            <div class="card-content bg-white rounded-lg shadow-sm px-5 py-3 flex justify-between items-center border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-[#E6F4F1] rounded-md shrink-0">
                        <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-10 h-10" alt="Post-Test">
                    </div>
                    <div class="card-text flex flex-col justify-center">
                        <p class="text-sm text-gray-500 leading-none">Post-Test</p>
                        <h3 class="font-semibold text-gray-900 text-base leading-tight mt-1">{{ $tna->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">
                            Periode: {{ \Carbon\Carbon::parse($tna->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tna->end_date)->format('d M Y') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Status: 
                            @if($postTestAttempt || ($status === \App\Enums\RealizationStatus::COMPLETED && $now->gt($endDate->copy()->addHour())))
                                <span class="text-red-500">Ditutup</span>
                            @elseif($isPostTestOpen)
                                <span class="text-green-600 font-semibold">Tersedia</span>
                            @else
                                <span class="text-gray-500">Menunggu Selesai</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($postTestAttempt)
                    <a href="{{ route('evaluasi2.review', [$registration, 'post-test']) }}" 
                       class="px-4 py-2 bg-[#17A2B8] text-white text-sm font-semibold rounded-md hover:bg-[#138496] transition-all duration-200 whitespace-nowrap min-w-[140px] text-center leading-none">
                        Review
                    </a>
                @elseif(!$isPostTestOpen)
                    <span class="px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-md whitespace-nowrap min-w-[140px] text-center leading-none">
                        Belum Tersedia
                    </span>
                @else
                    <a href="{{ route('evaluasi2.quiz.form', [$registration, 'post-test']) }}" 
                       class="px-4 py-2 bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm font-semibold rounded-md transition whitespace-nowrap min-w-[140px] text-center leading-none">
                       Kerjakan
                    </a>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">Tidak ada kuis yang tersedia.</p>
        @endforelse
    </div>
@endsection
