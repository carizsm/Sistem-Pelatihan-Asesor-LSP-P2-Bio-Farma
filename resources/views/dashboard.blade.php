@extends('layouts.trainee')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Peserta')

@section('content')
    {{-- Sambutan --}}
    <h2 class="text-xl font-semibold">Selamat Datang, <b>{{ Auth::user()->name ?? 'Peserta' }}</b> 👋</h2>

    {{-- Informasi Peserta --}}
    <p class="text-sm text-gray-600 mt-4 mb-4">Informasi Peserta</p>

    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-6">
        {{-- Avatar --}}
        <div class="bg-[#D9E7E9] rounded-lg w-32 h-32 flex items-center justify-center shadow-inner">
            <img src="{{ asset('icons/Avatar_Large.svg') }}" class="w-20 h-20" alt="Avatar">
        </div>

        {{-- Info Peserta --}}
        <div class="flex flex-col space-y-2 text-base">
            <div class="flex">
                <span class="w-32 font-semibold text-gray-700">Nama</span>
                <span class="font-bold text-gray-900">: {{ Auth::user()->name ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-gray-700">NIK</span>
                <span class="font-bold text-gray-900">: {{ Auth::user()->nik ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-gray-700">Jabatan</span>
                <span class="font-bold text-gray-900">: {{ Auth::user()->position->position_name ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-gray-700">Unit Kerja</span>
                <span class="font-bold text-gray-900">: {{ Auth::user()->unit->unit_name ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- TUGAS --}}
    <div class="mt-10"> 
        <h2 class="text-xl font-semibold mb-4">Tugas Anda</h2> 
        <p class="text-sm text-gray-600 mb-4">Daftar tugas yang tersedia</p> 

        {{-- Card tugas --}} 
        <div class="bg-[#F3F3F3] rounded-xl shadow-sm p-6 space-y-4"> 
            @forelse($tasks as $registration)
                @php
                    $tna = $registration->tna;
                    $hasFeedback = $registration->feedbackResult;
                    $hasPreTest = $registration->quizAttempts->where('type', 'pre-test')->isNotEmpty();
                    $hasPostTest = $registration->quizAttempts->where('type', 'post-test')->isNotEmpty();
                    $now = now();
                    $startDate = \Carbon\Carbon::parse($tna->start_date);
                    $endDate = \Carbon\Carbon::parse($tna->end_date);
                @endphp

                {{-- Pre-Test Task --}}
                @if(!$hasPreTest && in_array($tna->realization_status, [\App\Enums\RealizationStatus::OPEN, \App\Enums\RealizationStatus::RUNNING]))
                    <div class="card-content bg-white rounded-lg shadow-sm px-5 py-3 flex justify-between items-center border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#E6F4F1] rounded-md shrink-0">
                                <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-10 h-10" alt="Pre-Test">
                            </div>
                            <div class="card-text flex flex-col justify-center">
                                <p class="text-sm text-gray-500 leading-none">Pre-Test</p>
                                <h3 class="font-semibold text-gray-900 text-base leading-tight mt-1">{{ $tna->name }}</h3>
                            </div>
                        </div>
                        <a href="{{ route('evaluasi2.quiz.form', [$registration, 'pre-test']) }}" 
                           class="card-button bg-[#F26E22] hover:bg-[#d65c1c] text-white text-center text-sm font-semibold min-w-[120px] px-4 py-2 rounded-md transition shrink-0">
                           Kerjakan
                        </a>
                    </div>
                @endif

                {{-- Post-Test Task --}}
                @if(!$hasPostTest && 
                    $tna->realization_status === \App\Enums\RealizationStatus::COMPLETED &&
                    $now->lte($endDate->copy()->addHour()))
                    <div class="card-content bg-white rounded-lg shadow-sm px-5 py-3 flex justify-between items-center border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#E6F4F1] rounded-md shrink-0">
                                <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-10 h-10" alt="Post-Test">
                            </div>
                            <div class="card-text flex flex-col justify-center">
                                <p class="text-sm text-gray-500 leading-none">Post-Test</p>
                                <h3 class="font-semibold text-gray-900 text-base leading-tight mt-1">{{ $tna->name }}</h3>
                            </div>
                        </div>
                        <a href="{{ route('evaluasi2.quiz.form', [$registration, 'post-test']) }}" 
                           class="card-button bg-[#F26E22] hover:bg-[#d65c1c] text-white text-center text-sm font-semibold min-w-[120px] px-4 py-2 rounded-md transition shrink-0">
                           Kerjakan
                        </a>
                    </div>
                @endif

                {{-- Feedback Task --}}
                @if(!$hasFeedback && $tna->realization_status === \App\Enums\RealizationStatus::COMPLETED && $now->gt($endDate))
                    <div class="card-content bg-white rounded-lg shadow-sm px-5 py-3 flex justify-between items-center border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#E6F4F1] rounded-md shrink-0">
                                <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-10 h-10" alt="Completed">
                            </div>
                            <div class="card-text flex flex-col justify-center">
                                <p class="text-sm text-gray-500 leading-none">Evaluasi 1</p>
                                <h3 class="font-semibold text-gray-900 text-base leading-tight mt-1">{{ $tna->name }}</h3>
                            </div>
                        </div>
                        <a href="{{ route('evaluasi1.form', $registration) }}" 
                           class="card-button bg-[#F26E22] hover:bg-[#d65c1c] text-white text-center text-sm font-semibold min-w-[120px] px-4 py-2 rounded-md transition shrink-0">
                           Kerjakan
                        </a>
                    </div>
                @endif
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500 text-base">Tidak ada tugas yang tersedia saat ini.</p>
                    <p class="text-gray-400 text-sm mt-2">Tugas akan muncul saat ada evaluasi atau kuis yang perlu dikerjakan.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
