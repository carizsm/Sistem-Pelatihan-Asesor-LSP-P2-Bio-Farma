<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Evaluasi 2')</title>
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
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('dashboard') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/dashboard.svg') }}" class="w-7 h-7 shrink-0" alt="Dashboard">
                        <span x-show="$store.sidebar.open">Dashboard</span>
                    </a>
                </li>

                <!-- Presensi -->
                <li>
                    <a href="{{ route('peserta.presensi') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.presensi') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/presensi.svg') }}" class="w-7 h-7 shrink-0" alt="Presensi">
                        <span x-show="$store.sidebar.open">Presensi</span>
                    </a>
                </li>

                <!-- Evaluasi 1 -->
                <li>
                    <a href="{{ route('peserta.evaluasi1') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.evaluasi1') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="$store.sidebar.open">Evaluasi 1</span>
                    </a>
                </li>

                <!-- Evaluasi 2 -->
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

        {{-- Profil + Logout (Bagian bawah sidebar) --}}
        <div class="flex items-center justify-between p-4 border-t border-gray-300 hover:bg-gray-100 transition">
            {{-- Profil --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('icons/Avatar.svg') }}" class="w-9 h-9 shrink-0 bg-[#D9E7E9] rounded-lg" alt="User">
                <div x-show="$store.sidebar.open">
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">Peserta</p>
                </div>
            </div>

            {{-- Tombol Logout --}}
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
                @yield('header', 'Evaluasi 2') 
            </h1> 
        </div>
        {{-- Isi Konten --}}
        <div class="mt-3"> 
            <h2 class="text-xl font-semibold mb-4">Daftar Evaluasi 2</h2> 
            <p class="text-sm text-gray-600 mb-4">Daftar tugas yang tersedia</p>
            {{-- Card tugas placeholder --}} 
            <div class="bg-[#F3F3F3] rounded-xl shadow-sm p-6 space-y-4"> 
                @forelse($registrations as $registration)
                    @php
                        $tna = $registration->tna;
                        
                        // Cek Attempt (Sudah pernah kerjakan?)
                        $preTestAttempt = $registration->quizAttempts->where('type', 'pre-test')->first();
                        $postTestAttempt = $registration->quizAttempts->where('type', 'post-test')->first();
                        
                        // --- SECURITY STATUS CHECK (LOGIC BARU) ---
                        // Import Enum kalau belum di-import di atas, atau pakai Full Namespace
                        $status = $tna->realization_status;
                        
                        // PRE-TEST: Buka saat Persiapan (OPEN) atau Sedang Jalan (RUNNING)
                        // Tutup kalau sudah Selesai (COMPLETED) atau Batal (CANCELED)
                        $isPreTestOpen = in_array($status, [
                            \App\Enums\RealizationStatus::OPEN, 
                            \App\Enums\RealizationStatus::RUNNING
                        ]);
                        
                        // POST-TEST: Buka HANYA saat Selesai (COMPLETED)
                        $isPostTestOpen = $status === \App\Enums\RealizationStatus::COMPLETED;
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
                               class="px-4 py-2 bg-[#17A2B8] text-white text-sm font-semibold rounded-md hover:bg-[#138496] transition-all duration-200 whitespace-nowrap min-w-[180px] text-center leading-none">
                                Review (Skor: {{ number_format($preTestAttempt->score, 0) }})
                            </a>
                        @elseif(!$isPreTestOpen)
                            <span class="px-4 py-2 bg-gray-100 text-gray-500 text-sm font-semibold rounded-md whitespace-nowrap min-w-[180px] text-center leading-none">
                                @if($status === \App\Enums\RealizationStatus::COMPLETED)
                                    Sudah Berakhir
                                @else
                                    Tidak Tersedia
                                @endif
                            </span>
                        @else
                            <a href="{{ route('evaluasi2.quiz.form', [$registration, 'pre-test']) }}" 
                               class="px-4 py-2 bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm font-semibold rounded-md transition whitespace-nowrap min-w-[180px] text-center leading-none">
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
                                    @if($isPostTestOpen) <span class="text-green-600 font-semibold">Tersedia</span>
                                    @else <span class="text-gray-500">Menunggu Selesai</span> @endif
                                </p>
                            </div>
                        </div>

                        @if($postTestAttempt)
                            <a href="{{ route('evaluasi2.review', [$registration, 'post-test']) }}" 
                               class="px-4 py-2 bg-[#17A2B8] text-white text-sm font-semibold rounded-md hover:bg-[#138496] transition-all duration-200 whitespace-nowrap min-w-[180px] text-center leading-none">
                                Review (Skor: {{ number_format($postTestAttempt->score, 0) }})
                            </a>
                        @elseif(!$isPostTestOpen)
                            <span class="px-4 py-2 bg-gray-100 text-gray-500 text-sm font-semibold rounded-md whitespace-nowrap min-w-[180px] text-center leading-none">
                                Belum Tersedia
                            </span>
                        @else
                            <a href="{{ route('evaluasi2.quiz.form', [$registration, 'post-test']) }}" 
                               class="px-4 py-2 bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm font-semibold rounded-md transition whitespace-nowrap min-w-[180px] text-center leading-none">
                               Kerjakan
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada kuis yang tersedia.</p>
                @endforelse
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