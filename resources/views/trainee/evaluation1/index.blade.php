@extends('layouts.trainee')

@section('title', 'Evaluasi 1')
@section('page_title', 'Evaluasi 1')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Daftar Evaluasi 1</h2> 
    <p class="text-sm text-gray-600 mb-4">Daftar feedback yang tersedia</p>
    
    {{-- Card tugas placeholder --}} 
    <div class="bg-[#F3F3F3] rounded-xl shadow-sm p-6 space-y-4"> 
        @forelse($registrations as $registration)
            @php
                $tna = $registration->tna;
                $hasFeedback = $registration->feedbackResult;
                
                // PRODUCTION: Hanya bisa akses setelah end_date
                $canAccess = $tna->realization_status === App\Enums\RealizationStatus::COMPLETED;
                
                // TESTING: Uncomment baris di bawah untuk testing (bisa akses kapan saja)
                // $canAccess = true;
            @endphp

            <div class="card-content bg-white rounded-lg shadow-sm px-5 py-3 flex justify-between items-center border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-[#E6F4F1] rounded-md shrink-0">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-10 h-10" alt="Evaluasi 1">
                    </div>
                    <div class="card-text flex flex-col justify-center">
                        <p class="text-sm text-gray-500 leading-none">Evaluasi 1</p>
                        <h3 class="font-semibold text-gray-900 text-base leading-tight mt-1">{{ $tna->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Periode: {{ \Carbon\Carbon::parse($tna->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tna->end_date)->format('d M Y') }}</p>
                    </div>
                </div>

                @if($hasFeedback)
                    <a href="{{ route('evaluasi1.review', $registration) }}" 
                       class="px-4 py-2 bg-[#17A2B8] text-white text-sm font-semibold rounded-md hover:bg-[#138496] transition-all duration-200 whitespace-nowrap min-w-[120px] text-center leading-none">
                        Review
                    </a>
                @elseif(!$canAccess)
                    <span class="px-4 py-2 bg-gray-100 text-gray-500 text-sm font-semibold rounded-md whitespace-nowrap min-w-[120px] text-center leading-none">
                        Kerjakan
                    </span>
                @else
                    <a href="{{ route('evaluasi1.form', $registration) }}" 
                       class="px-4 py-2 bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm font-semibold rounded-md transition whitespace-nowrap min-w-[120px] text-center leading-none">
                       Kerjakan
                    </a>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">Tidak ada evaluasi yang tersedia.</p>
        @endforelse
    </div>
@endsection
