<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Evaluasi 2</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#EEE8E5] font-sans text-gray-800 min-h-screen">

    {{-- Sidebar --}}
    <aside 
        x-data 
        :class="$store.sidebar.open ? 'w-64' : 'w-20'" 
        class="bg-[#F3F3F3] fixed left-0 top-0 h-full flex flex-col justify-between transition-all duration-300 shadow-sm z-50 overflow-y-auto"
    >
        {{-- Bagian Atas --}}
        <div>
            {{-- Tombol Toggle Sidebar --}}
            <div class="flex items-center gap-3 px-4 py-3 cursor-pointer">
                <button @click="$store.sidebar.toggle()" class="p-2 rounded-lg hover:bg-gray-200 transition">
                    <img src="{{ asset('icons/Toggle Sidebar.svg') }}" class="w-7 h-7 shrink-0" alt="toggle">
                </button>
            </div>

            {{-- Logo --}}
            <div class="flex items-center justify-center py-3 border-b border-gray-300">
                <img x-show="$store.sidebar.open" src="{{ asset('images/logo-lsp.png') }}" class="w-36" alt="Logo LSP">
            </div>

            {{-- Menu Sidebar --}}
            <nav class="flex flex-col gap-2 mt-4">
                <li>
                    <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition hover:bg-gray-200">
                        <img src="{{ asset('icons/dashboard.svg') }}" class="w-7 h-7 shrink-0" alt="Dashboard">
                        <span x-show="$store.sidebar.open">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.presensi') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition hover:bg-gray-200">
                        <img src="{{ asset('icons/presensi.svg') }}" class="w-7 h-7 shrink-0" alt="Presensi">
                        <span x-show="$store.sidebar.open">Presensi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi1') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition hover:bg-gray-200">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="$store.sidebar.open">Evaluasi 1</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi2') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition bg-[#F4E5DD] text-[#F26E22] font-semibold">
                        <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 2">
                        <span x-show="$store.sidebar.open">Evaluasi 2</span>
                    </a>
                </li>
            </nav>
        </div>

        {{-- Profil + Logout --}}
        <div class="flex items-center justify-between p-4 border-t border-gray-300 hover:bg-gray-100 transition">
            <div class="flex items-center gap-3">
                <img src="{{ asset('icons/Avatar.svg') }}" class="w-9 h-9 shrink-0 bg-[#D9E7E9] rounded-lg" alt="User">
                <div x-show="$store.sidebar.open">
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">Peserta</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" x-show="$store.sidebar.open">
                @csrf
                <button type="submit" 
                    class="text-red-500 hover:text-red-700 transition" 
                    title="Logout">
                    <img src="{{ asset('icons/logout.svg') }}" class="w-6 h-6" alt="Logout">
                </button>
            </form>
        </div>
    </aside>

    {{-- Konten Utama --}}
    <main 
        x-data="quizNavigation()"
        :class="$store.sidebar.open ? 'ml-64' : 'ml-20'"
        class="flex-1 flex flex-col min-h-screen transition-all duration-300"
    >
        {{-- Header --}}
        <div class="bg-[#F3F3F3] px-6 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                {{-- Spacer kiri untuk balance --}}
                <div class="w-32"></div>

                {{-- Title & Subtitle di tengah --}}
                <div class="flex-1 text-center">
                    <h1 class="font-bold text-xl text-gray-800">
                        {{ ucfirst(str_replace('-', ' ', $type)) }} - {{ $tna->name }}
                        @if(isset($attempt))
                            <span class="text-sm text-green-600 font-normal">(Review)</span>
                        @endif
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        @if(!isset($attempt))
                            <span class="text-[#F26E22] font-semibold" x-text="'Soal ' + (currentPage + 1) + ' dari ' + totalPages"></span>
                        @else
                            Hasil Quiz Anda
                        @endif
                    </p>
                </div>

                {{-- Button kanan --}}
                <div class="w-32 flex justify-end"> 
                    @if(!isset($attempt))
                        <button type="button" @click="submitQuiz()"
                            class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition whitespace-nowrap">
                            Selesai
                        </button>
                    @else
                        <a href="{{ route('peserta.evaluasi2') }}"
                           class="inline-flex items-center justify-center bg-gray-500 text-white text-sm px-4 h-9 rounded-lg font-semibold hover:bg-gray-600 transition whitespace-nowrap leading-none">
                            Kembali
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Summary Score (mode review) --}}
        @if(isset($attempt))
            <div class="px-6 pt-4">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Skor Anda</p>
                            <p class="text-3xl font-bold text-blue-600">{{ number_format($attempt->score, 0) }}</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Jawaban Benar</p>
                            <p class="text-3xl font-bold text-green-600">
                                {{ $attempt->traineeAnswers->filter(fn($a) => $a->quizAnswer->is_correct)->count() }} / {{ $attempt->traineeAnswers->count() }}
                            </p>
                        </div>
                        <div class="p-4 {{ $attempt->score >= ($tna->passing_score ?? 70) ? 'bg-green-50' : 'bg-red-50' }} rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <p class="text-xl font-bold {{ $attempt->score >= ($tna->passing_score ?? 70) ? 'text-green-600' : 'text-red-600' }}">
                                {{ $attempt->score >= ($tna->passing_score ?? 70) ? '✓ LULUS' : '✗ TIDAK LULUS' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="flex-1 px-6 py-6 flex gap-4 items-stretch">
            {{-- Question Panel --}}
            <div class="flex-1 flex flex-col min-h-0">
                @if(!isset($attempt))
                    {{-- MODE PENGERJAAN --}}
                    <form id="quiz-form" method="POST" action="{{ route('evaluasi2.quiz.store', [$registration, $type]) }}" class="flex-1 flex flex-col min-h-0">
                        @csrf
                        
                        @if(empty($questions))
                            <div class="bg-white rounded-xl shadow-sm p-12 text-center flex-1 flex items-center justify-center">
                                <p class="text-gray-500 text-lg">Belum ada soal yang tersedia untuk {{ $type }} ini.</p>
                            </div>
                        @else
                            @foreach($questions as $index => $question)
                                <div x-show="currentPage === {{ $index }}" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform translate-x-4"
                                     x-transition:enter-end="opacity-100 transform translate-x-0"
                                     class="bg-white rounded-xl shadow-sm p-8 flex flex-col flex-1 min-h-0">
                                    
                                    {{-- Question Content - dengan overflow scroll --}}
                                    <div class="flex-1 flex flex-col min-h-0 overflow-y-auto pr-2">
                                        {{-- Question Header --}}
                                        <div class="mb-6 pb-4 border-b border-gray-200 flex-shrink-0">
                                            <div class="flex items-center gap-3 mb-3">
                                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#F26E22] text-white font-bold">
                                                    {{ $question->question_number }}
                                                </span>
                                                <span class="text-sm text-gray-500">Pertanyaan {{ $question->question_number }} dari {{ count($questions) }}</span>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 leading-relaxed">
                                                {{ $question->question }}
                                            </h3>
                                        </div>

                                        {{-- Answers --}}
                                        <div class="space-y-3">
                                            @if(empty($question->quizAnswers) || $question->quizAnswers->isEmpty())
                                                <p class="text-sm text-red-500">Tidak ada opsi jawaban untuk soal ini.</p>
                                            @else
                                                @foreach($question->quizAnswers->sortBy('answer_order') as $answer)
                                                    <label class="flex items-start gap-4 p-4 rounded-lg border-2 border-gray-200 hover:border-[#F26E22] hover:bg-orange-50 cursor-pointer transition group"
                                                           :class="getAnswerClass({{ $question->id }}, {{ $answer->id }})">
                                                        <input type="radio" 
                                                               name="answers[{{ $question->id }}]" 
                                                               value="{{ $answer->id }}" 
                                                               required
                                                               @click="selectAnswer({{ $question->id }}, {{ $answer->id }})"
                                                               class="mt-1 w-5 h-5 text-[#F26E22] focus:ring-[#F26E22] cursor-pointer flex-shrink-0">
                                                        <div class="flex-1">
                                                            <span class="text-base text-gray-800 group-hover:text-gray-900">
                                                                <span class="font-semibold">{{ chr(64 + $answer->answer_order) }}.</span> {{ $answer->answer }}
                                                            </span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Navigation Buttons (fixed di bawah) --}}
                                    <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200 flex-shrink-0">
                                        <button type="button" 
                                                @click="prevPage()" 
                                                x-show="currentPage > 0"
                                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                            ← Sebelumnya
                                        </button>
                                        <div x-show="currentPage === 0" class="w-24"></div>
                                        
                                        <button type="button" 
                                                @click="nextPage()" 
                                                x-show="currentPage < totalPages - 1"
                                                class="px-6 py-2 bg-[#F26E22] text-white rounded-lg hover:bg-[#d65c1c] transition font-semibold">
                                            Selanjutnya →
                                        </button>
                                        <div x-show="currentPage === totalPages - 1" class="w-24"></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </form>
                @else
                    {{-- MODE REVIEW --}}
                    <div class="flex-1 flex flex-col min-h-0">
                        @foreach($attempt->traineeAnswers as $index => $traineeAnswer)
                            @php
                                $question = $traineeAnswer->quizQuestion;
                                $userAnswer = $traineeAnswer->quizAnswer;
                                $isCorrect = $userAnswer->is_correct;
                            @endphp

                            <div x-show="currentPage === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform translate-x-4"
                                 x-transition:enter-end="opacity-100 transform translate-x-0"
                                 class="bg-white rounded-xl shadow-sm p-8 flex flex-col flex-1 min-h-0 {{ $isCorrect ? 'border-2 border-green-300' : 'border-2 border-red-300' }}">
                                
                                {{-- Question Content - dengan overflow scroll --}}
                                <div class="flex-1 flex flex-col min-h-0 overflow-y-auto pr-2">
                                    {{-- Question Header --}}
                                    <div class="mb-6 pb-4 border-b border-gray-200 flex-shrink-0">
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $isCorrect ? 'bg-green-500' : 'bg-red-500' }} text-white font-bold">
                                                {{ $question->question_number }}
                                            </span>
                                            <span class="text-sm {{ $isCorrect ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                                {{ $isCorrect ? '✓ Jawaban Benar' : '✗ Jawaban Salah' }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 leading-relaxed">
                                            {{ $question->question }}
                                        </h3>
                                    </div>

                                    {{-- Answers --}}
                                    <div class="space-y-3">
                                        @foreach($question->quizAnswers->sortBy('answer_order') as $answer)
                                            @php
                                                $isUserAnswer = $answer->id === $userAnswer->id;
                                                $isCorrectAnswer = $answer->is_correct;
                                            @endphp

                                            <div class="flex items-start gap-4 p-4 rounded-lg border-2
                                                {{ $isCorrectAnswer ? 'border-green-500 bg-green-50' : 'border-gray-200' }}
                                                {{ $isUserAnswer && !$isCorrect ? 'border-red-500 bg-red-50' : '' }}">
                                                <div class="mt-1 flex-shrink-0">
                                                    @if($isCorrectAnswer)
                                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    @elseif($isUserAnswer && !$isCorrect)
                                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    @else
                                                        <div class="w-5 h-5"></div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <span class="text-base {{ $isCorrectAnswer || ($isUserAnswer && !$isCorrect) ? 'font-semibold' : '' }}">
                                                        <span class="font-bold">{{ chr(64 + $answer->answer_order) }}.</span> {{ $answer->answer }}
                                                    </span>
                                                    @if($isUserAnswer && !$isCorrect)
                                                        <p class="text-xs text-red-600 mt-1">Jawaban Anda</p>
                                                    @endif
                                                    @if($isCorrectAnswer && !$isUserAnswer)
                                                        <p class="text-xs text-green-600 mt-1">Jawaban yang Benar</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Navigation Buttons (fixed di bawah) --}}
                                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200 flex-shrink-0">
                                    <button type="button" 
                                            @click="prevPage()" 
                                            x-show="currentPage > 0"
                                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                        ← Sebelumnya
                                    </button>
                                    <div x-show="currentPage === 0" class="w-24"></div>
                                    
                                    <button type="button" 
                                            @click="nextPage()" 
                                            x-show="currentPage < totalPages - 1"
                                            class="px-6 py-2 bg-[#F26E22] text-white rounded-lg hover:bg-[#d65c1c] transition font-semibold">
                                        Selanjutnya →
                                    </button>
                                    <div x-show="currentPage === totalPages - 1" class="w-24"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Question Navigator Panel (Moodle-style) - Self-contained height --}}
            <div class="w-80 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
                    <h3 class="font-bold text-gray-800 mb-4">Navigasi Soal</h3>
                    
                    <div class="grid grid-cols-5 gap-2 mb-6">
                        @if(!isset($attempt))
                            @foreach($questions as $index => $question)
                                <button type="button"
                                        @click="goToPage({{ $index }})"
                                        :class="{
                                            'bg-[#F26E22] text-white': currentPage === {{ $index }},
                                            'bg-green-100 text-green-700 border-green-400': currentPage !== {{ $index }} && isAnswered({{ $question->id }}),
                                            'bg-gray-100 text-gray-600 border-gray-300': currentPage !== {{ $index }} && !isAnswered({{ $question->id }})
                                        }"
                                        class="w-12 h-12 rounded-lg border-2 font-semibold hover:shadow-md transition flex items-center justify-center">
                                    {{ $question->question_number }}
                                </button>
                            @endforeach
                        @else
                            @foreach($attempt->traineeAnswers as $index => $traineeAnswer)
                                @php $isCorrect = $traineeAnswer->quizAnswer->is_correct; @endphp
                                <button type="button"
                                        @click="goToPage({{ $index }})"
                                        :class="{
                                            'bg-[#F26E22] text-white border-[#F26E22]': currentPage === {{ $index }},
                                            'bg-green-100 text-green-700 border-green-400': currentPage !== {{ $index }} && {{ $isCorrect ? 'true' : 'false' }},
                                            'bg-red-100 text-red-700 border-red-400': currentPage !== {{ $index }} && {{ $isCorrect ? 'false' : 'true' }}
                                        }"
                                        class="w-12 h-12 rounded-lg border-2 font-semibold hover:shadow-md transition flex items-center justify-center">
                                    {{ $traineeAnswer->quizQuestion->question_number }}
                                </button>
                            @endforeach
                        @endif
                    </div>

                    {{-- Progress (tanpa Legend) --}}
                    @if(!isset($attempt))
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-600">Progress</span>
                                <span class="font-semibold" x-text="Math.round((answeredCount / totalPages) * 100) + '%'"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-[#F26E22] h-2 rounded-full transition-all duration-300" 
                                     :style="'width: ' + ((answeredCount / totalPages) * 100) + '%'"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2" x-text="answeredCount + ' dari ' + totalPages + ' soal dijawab'"></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Submit Quiz --}}
        <div x-show="showSubmitModal" 
             x-cloak
             @click.self="showSubmitModal = false"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4"
             style="display: none;">
            <div @click.stop 
                 x-show="showSubmitModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                
                {{-- Icon & Title --}}
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-[#F26E22]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Selesaikan Quiz?</h3>
                    <p class="text-sm text-gray-600">Pastikan semua jawaban sudah benar sebelum mengirim</p>
                </div>

                {{-- Progress Info --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Progress Pengerjaan</span>
                        <span class="text-sm font-bold" x-text="answeredCount + '/' + totalPages"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                        <div class="bg-[#F26E22] h-2 rounded-full transition-all duration-300" 
                             :style="'width: ' + ((answeredCount / totalPages) * 100) + '%'"></div>
                    </div>
                    
                    {{-- Warning jika belum lengkap --}}
                    <div x-show="answeredCount < totalPages" 
                         class="mt-3 flex items-start gap-2 text-amber-700 bg-amber-50 rounded-lg p-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold">Perhatian!</p>
                            <p class="text-xs mt-0.5" x-text="'Masih ada ' + (totalPages - answeredCount) + ' soal yang belum dijawab'"></p>
                        </div>
                    </div>

                    {{-- Checkmark jika sudah lengkap --}}
                    <div x-show="answeredCount === totalPages" 
                         class="mt-3 flex items-center gap-2 text-green-700 bg-green-50 rounded-lg p-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-semibold">Semua soal sudah dijawab!</p>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button @click="showSubmitModal = false"
                            type="button"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition">
                        Periksa Lagi
                    </button>
                    <button @click="confirmSubmit()"
                            type="button"
                            class="flex-1 px-4 py-3 bg-[#F26E22] text-white rounded-xl font-semibold hover:bg-[#d65c1c] transition">
                        Ya, Selesaikan
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        // Alpine Global Store untuk Sidebar State
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            });
        });

        function quizNavigation() {
            return {
                currentPage: 0,
                totalPages: {{ isset($attempt) ? $attempt->traineeAnswers->count() : count($questions) }},
                answers: {},
                answeredCount: 0,
                isSaving: false,
                showSubmitModal: false,

                init() {
                    @if(!isset($attempt))
                        // MODE PENGERJAAN
                        // Load saved answers from localStorage on init
                        const savedAnswers = localStorage.getItem('quiz_{{ $registration->id }}_{{ $type }}');
                        if (savedAnswers) {
                            this.answers = JSON.parse(savedAnswers);
                            this.restoreAnswersToForm();
                            this.updateAnsweredCount();
                        }

                        // Restore last viewed page dari localStorage
                        const savedPage = localStorage.getItem('quiz_{{ $registration->id }}_{{ $type }}_page');
                        if (savedPage !== null) {
                            this.currentPage = parseInt(savedPage);
                        }

                        // Check existing answers from form
                        document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                            const questionId = input.name.match(/\d+/)[0];
                            this.answers[questionId] = input.value;
                        });
                        this.updateAnsweredCount();
                    @else
                        // MODE REVIEW
                        // Restore last viewed page dari localStorage
                        const savedReviewPage = localStorage.getItem('quiz_{{ $registration->id }}_{{ $type }}_review_page');
                        if (savedReviewPage !== null) {
                            this.currentPage = parseInt(savedReviewPage);
                        }
                        
                        // Pastikan currentPage tidak melebihi batas
                        if (this.currentPage >= this.totalPages) {
                            this.currentPage = 0;
                        }
                    @endif
                },

                restoreAnswersToForm() {
                    // Restore radio button selections from localStorage
                    Object.keys(this.answers).forEach(questionId => {
                        const answerId = this.answers[questionId];
                        const radio = document.querySelector(`input[name="answers[${questionId}]"][value="${answerId}"]`);
                        if (radio) {
                            radio.checked = true;
                        }
                    });
                },

                selectAnswer(questionId, answerId) {
                    this.answers[questionId] = answerId;
                    this.updateAnsweredCount();
                    this.saveToLocalStorage();
                },

                saveToLocalStorage() {
                    // Save to localStorage immediately
                    localStorage.setItem('quiz_{{ $registration->id }}_{{ $type }}', JSON.stringify(this.answers));
                },

                saveCurrentPage() {
                    // Save current page to localStorage (untuk mode pengerjaan DAN review)
                    @if(!isset($attempt))
                        localStorage.setItem('quiz_{{ $registration->id }}_{{ $type }}_page', this.currentPage);
                    @else
                        localStorage.setItem('quiz_{{ $registration->id }}_{{ $type }}_review_page', this.currentPage);
                    @endif
                },

                isAnswered(questionId) {
                    return this.answers[questionId] !== undefined;
                },

                updateAnsweredCount() {
                    this.answeredCount = Object.keys(this.answers).length;
                },

                getAnswerClass(questionId, answerId) {
                    if (this.answers[questionId] == answerId) {
                        return 'border-[#F26E22] bg-orange-50';
                    }
                    return '';
                },

                nextPage() {
                    if (this.currentPage < this.totalPages - 1) {
                        this.currentPage++;
                        this.saveCurrentPage();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevPage() {
                    if (this.currentPage > 0) {
                        this.currentPage--;
                        this.saveCurrentPage();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                goToPage(index) {
                    this.currentPage = index;
                    this.saveCurrentPage();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                submitQuiz() {
                    // Tampilkan modal konfirmasi
                    this.showSubmitModal = true;
                },

                confirmSubmit() {
                    // Submit quiz setelah konfirmasi dari modal
                    this.showSubmitModal = false;
                    
                    // Clear localStorage after submit
                    localStorage.removeItem('quiz_{{ $registration->id }}_{{ $type }}');
                    localStorage.removeItem('quiz_{{ $registration->id }}_{{ $type }}_page');
                    
                    // Submit form
                    document.getElementById('quiz-form').submit();
                }
            }
        }

        // Auto-save on page unload (backup)
        window.addEventListener('beforeunload', function() {
            const mainElement = document.querySelector('[x-data*="quizNavigation"]');
            if (!mainElement) return;
            
            const quizData = Alpine.$data(mainElement);
            if (!quizData) return;

            @if(!isset($attempt))
                // Mode pengerjaan: save answers dan page
                if (quizData.answers) {
                    localStorage.setItem('quiz_{{ $registration->id }}_{{ $type }}', JSON.stringify(quizData.answers));
                }
                if (quizData.currentPage !== undefined) {
                    localStorage.setItem('quiz_{{ $registration->id }}_{{ $type }}_page', quizData.currentPage);
                }
            @else
                // Mode review: hanya save page position
                if (quizData.currentPage !== undefined) {
                    localStorage.setItem('quiz_{{ $registration->id }}_{{ $type }}_review_page', quizData.currentPage);
                }
            @endif
        });
    </script>

    <style>
        [x-cloak] { 
            display: none !important; 
        }
    </style>

    @include('components.toast-notification')
</body>
</html>