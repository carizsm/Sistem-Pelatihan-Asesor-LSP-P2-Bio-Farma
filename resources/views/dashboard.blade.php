<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#EEE8E5] font-sans text-gray-800 min-h-screen">

    {{-- Sidebar --}}
    <aside 
        x-data 
        :class="$store.sidebar.open ? 'w-64' : 'w-20'" 
        class="bg-[#F3F3F3] fixed left-0 top-0 h-screen flex flex-col justify-between transition-all duration-300 shadow-sm z-50 overflow-y-auto"
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
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('dashboard') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/dashboard.svg') }}" class="w-7 h-7 shrink-0" alt="Dashboard">
                        <span x-show="$store.sidebar.open">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.presensi') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.presensi') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/presensi.svg') }}" class="w-7 h-7 shrink-0" alt="Presensi">
                        <span x-show="$store.sidebar.open">Presensi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi1') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.evaluasi1') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="$store.sidebar.open">Evaluasi 1</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi2') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.evaluasi2') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
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
        x-data
        :class="$store.sidebar.open ? 'ml-64' : 'ml-20'"
        class="flex-1 px-6 pb-6 pt-2 transition-all duration-300"
    >

        {{-- Navbar Atas --}}
        <div class="flex items-center justify-center bg-[#F3F3F3] rounded-xl p-2 shadow-sm mb-3 px-6"> 
            <h1 class="font-semibold text-lg"> 
                @yield('header', 'Dashboard Peserta') 
            </h1> 
        </div>

        {{-- Isi Konten --}}
        <div class="space-y-4">
            {{-- Sambutan --}}
            <h2 class="text-xl font-semibold">Selamat Datang, <b>{{ Auth::user()->name ?? 'Peserta' }}</b> 👋</h2>

            {{-- Informasi Peserta --}}
            <p class="text-sm text-gray-600">Informasi Peserta</p>

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
                                   class="card-button bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm font-semibold px-4 py-2 rounded-md transition shrink-0">
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
                                <a href="{{ route('evaluasi1.review', $registration) }}" 
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
        </div>

        @yield('content')
    </main>

    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            });
        });
    </script>
</body>
</html>
