<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Presensi')</title>
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
                @yield('header', 'Presensi Peserta') 
            </h1> 
        </div>

        {{-- Isi Konten --}}
        <div class="mt-3"> 
            <h2 class="text-xl font-semibold mb-4">Presensi Pelatihan Asesor LSP</h2> 
            <p class="text-sm text-gray-600 mb-4">Presensi Peserta</p> 
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mb-4 px-4 py-3 bg-blue-100 text-blue-700 rounded-lg">
                {{ session('info') }}
            </div>
        @endif

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
