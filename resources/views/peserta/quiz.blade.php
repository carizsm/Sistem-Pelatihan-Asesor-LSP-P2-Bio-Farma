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
        x-data="{ open: false }" 
        :class="open ? 'w-64' : 'w-20'" 
        class="bg-[#F3F3F3] fixed left-0 top-0 h-full flex flex-col justify-between transition-all duration-300 shadow-sm z-50 overflow-y-auto"
    >
        {{-- Bagian Atas --}}
        <div>
            {{-- Tombol Toggle Sidebar --}}
            <div class="flex items-center gap-3 px-4 py-3 cursor-pointer">
                <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-200 transition">
                    <img src="{{ asset('icons/Toggle Sidebar.svg') }}" class="w-7 h-7 shrink-0" alt="toggle">
                </button>
            </div>

            {{-- Logo --}}
            <div class="flex items-center justify-center py-3 border-b border-gray-300">
                <img x-show="open" src="{{ asset('images/logo-lsp.png') }}" class="w-36" alt="Logo LSP">
            </div>

            {{-- Menu Sidebar --}}
            <nav class="flex flex-col gap-2 mt-4">
                <li>
                    <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition hover:bg-gray-200">
                        <img src="{{ asset('icons/dashboard.svg') }}" class="w-7 h-7 shrink-0" alt="Dashboard">
                        <span x-show="open">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.presensi') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition hover:bg-gray-200">
                        <img src="{{ asset('icons/presensi.svg') }}" class="w-7 h-7 shrink-0" alt="Presensi">
                        <span x-show="open">Presensi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi1') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition hover:bg-gray-200">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="open">Evaluasi 1</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi2') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition bg-[#F4E5DD] text-[#F26E22] font-semibold">
                        <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 2">
                        <span x-show="open">Evaluasi 2</span>
                    </a>
                </li>
            </nav>
        </div>

        {{-- Profil + Logout --}}
        <div class="flex items-center justify-between p-4 border-t border-gray-300 hover:bg-gray-100 transition">
            <div class="flex items-center gap-3">
                <img src="{{ asset('icons/Avatar.svg') }}" class="w-9 h-9 shrink-0 bg-[#D9E7E9] rounded-lg" alt="User">
                <div x-show="open">
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">Peserta</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" x-show="open">
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
        x-data="{ sidebarOpen: false }"
        :class="sidebarOpen ? 'ml-64' : 'ml-20'"
        class="flex-1 flex flex-col min-h-screen transition-all duration-300"
    >
        <div class="flex-1 px-6 pb-6 pt-2 flex flex-col space-y-3">
            {{-- Navbar Atas --}}
            <div class="flex items-center justify-between bg-[#F3F3F3] rounded-xl p-2 shadow-sm mb-0 mt-0">
                <h1 class="font-semibold text-lg text-center flex-1">
                    {{ ucfirst(str_replace('-', ' ', $type)) }} - {{ $tna->name }}
                    @if(isset($attempt))
                        <span class="text-sm text-green-600">(Review)</span>
                    @endif
                </h1>

                <div class="px-6"> 
                    @if(!isset($attempt))
                        <button type="submit" form="quiz-form"
                            class="bg-[#F26E22] text-white text-sm px-3 py-1 rounded-lg font-semibold hover:bg-[#d65c1c] transition whitespace-nowrap">
                            Selesaikan
                        </button>
                    @else
                        <a href="{{ route('peserta.evaluasi2') }}"
                            class="bg-gray-500 text-white text-sm px-3 py-1 rounded-lg font-semibold hover:bg-gray-600 transition whitespace-nowrap">
                            Kembali
                        </a>
                    @endif
                </div>
            </div>

            {{-- Summary Score (hanya tampil di mode review) --}}
            @if(isset($attempt))
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
            @endif

            {{-- Isi Konten Quiz --}}
            <div class="bg-white rounded-xl shadow-sm p-8 flex-1 overflow-y-auto">
                @if(!isset($attempt))
                    {{-- MODE PENGERJAAN --}}
                    <form id="quiz-form" method="POST" action="{{ route('evaluasi2.quiz.store', [$registration, $type]) }}">
                        @csrf
                        
                        @if($questions->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-gray-500 text-lg">Belum ada soal yang tersedia untuk {{ $type }} ini.</p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($questions as $question)
                                    <div class="quiz-item border-b border-gray-200 pb-6">
                                        {{-- Question --}}
                                        <div class="flex gap-4 mb-4">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F26E22] text-white font-bold text-sm">
                                                    {{ $question->question_number }}
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-base font-semibold text-gray-900 leading-relaxed">
                                                    {{ $question->question }}
                                                </h3>
                                            </div>
                                        </div>

                                        {{-- Answers --}}
                                        <div class="ml-12 space-y-3">
                                            @if($question->quizAnswers->isEmpty())
                                                <p class="text-sm text-red-500">Tidak ada opsi jawaban untuk soal ini.</p>
                                            @else
                                                @foreach($question->quizAnswers->sortBy('answer_order') as $answer)
                                                    <label class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition group">
                                                        <input type="radio" 
                                                               name="answers[{{ $question->id }}]" 
                                                               value="{{ $answer->id }}" 
                                                               required
                                                               class="mt-1 w-4 h-4 text-[#F26E22] focus:ring-[#F26E22] cursor-pointer">
                                                        <span class="flex-1 text-sm text-gray-700 group-hover:text-gray-900 leading-relaxed">
                                                            <span class="font-semibold">{{ chr(64 + $answer->answer_order) }}.</span> {{ $answer->answer }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </form>
                @else
                    {{-- MODE REVIEW --}}
                    <h2 class="text-lg font-semibold mb-4">Review Jawaban</h2>
                    <div class="space-y-6">
                        @foreach($attempt->traineeAnswers as $traineeAnswer)
                            @php
                                $question = $traineeAnswer->quizQuestion;
                                $userAnswer = $traineeAnswer->quizAnswer;
                                $isCorrect = $userAnswer->is_correct;
                            @endphp

                            <div class="quiz-item border-b border-gray-200 pb-6 {{ $isCorrect ? 'bg-green-50' : 'bg-red-50' }} p-4 rounded-lg">
                                {{-- Question --}}
                                <div class="flex gap-4 mb-4">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $isCorrect ? 'bg-green-500' : 'bg-red-500' }} text-white font-bold text-sm">
                                            {{ $question->question_number }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-900 leading-relaxed">
                                            {{ $question->question }}
                                        </h3>
                                    </div>
                                </div>

                                {{-- Answers --}}
                                <div class="ml-12 space-y-2">
                                    @foreach($question->quizAnswers->sortBy('answer_order') as $answer)
                                        @php
                                            $isUserAnswer = $answer->id === $userAnswer->id;
                                            $isCorrectAnswer = $answer->is_correct;
                                        @endphp

                                        <div class="flex items-start gap-3 p-3 rounded-lg
                                            {{ $isCorrectAnswer ? 'bg-green-100 border-2 border-green-500' : '' }}
                                            {{ $isUserAnswer && !$isCorrect ? 'bg-red-100 border-2 border-red-500' : '' }}
                                        ">
                                            <span class="text-sm font-bold">
                                                {{ chr(64 + $answer->answer_order) }}.
                                            </span>
                                            <span class="flex-1 text-sm text-gray-700">
                                                {{ $answer->answer }}
                                            </span>
                                            @if($isUserAnswer)
                                                <span class="text-xs font-semibold {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $isCorrect ? '✓ Jawaban Anda' : '✗ Jawaban Anda' }}
                                                </span>
                                            @endif
                                            @if($isCorrectAnswer && !$isUserAnswer)
                                                <span class="text-xs font-semibold text-green-600">
                                                    ✓ Jawaban Benar
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Info Card --}}
        <div class="flex items-center justify-center bg-[#F3F3F3] rounded-xl shadow-sm mx-6 mb-2">
            <div class="flex items-center gap-3 px-6 py-2">
                <span class="text-sm text-gray-600 font-medium">
                    @if(isset($attempt))
                        Review Hasil Quiz
                    @elseif($questions->isNotEmpty())
                        Total {{ $questions->count() }} Soal
                    @endif
                </span>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/alpinejs" defer></script>
</body>
</html>