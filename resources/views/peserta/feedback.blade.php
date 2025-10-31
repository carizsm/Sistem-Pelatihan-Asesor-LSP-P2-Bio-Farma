<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Feedback Evaluasi 1')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#EEE8E5] font-sans text-gray-800 min-h-screen flex flex-col">

    <div class="flex flex-1">
        {{-- Sidebar --}}
        <aside 
            x-data="{ open: false }" 
            :class="open ? 'w-64' : 'w-20'" 
            class="bg-[#F3F3F3] h-screen flex flex-col justify-between transition-all duration-300 shadow-sm"
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
                        class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                            {{ request()->routeIs('dashboard') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                            <img src="{{ asset('icons/dashboard.svg') }}" class="w-7 h-7 shrink-0" alt="Dashboard">
                            <span x-show="open">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('peserta.presensi') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                            {{ request()->routeIs('peserta.presensi') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                            <img src="{{ asset('icons/presensi.svg') }}" class="w-7 h-7 shrink-0" alt="Presensi">
                            <span x-show="open">Presensi</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('peserta.evaluasi1') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                            {{ request()->routeIs('peserta.evaluasi1') || request()->routeIs('peserta.feedback') ? 
                                'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                            <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                            <span x-show="open">Evaluasi 1</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('peserta.evaluasi2') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                            {{ request()->routeIs('peserta.quiz') || request()->routeIs('peserta.evaluasi2') ? 
                            'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
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
        <main class="flex-1 flex flex-col">
            <div class="flex-1 px-6 pb-6 pt-2 flex flex-col space-y-3">
                {{-- Navbar Atas --}}
                <div class="flex items-center justify-between bg-[#F3F3F3] rounded-xl p-2 shadow-sm mb-0 mt-0 relative">
                    <div class="flex items-center gap-3 px-5">
                        <button class="p-1 rounded-lg bg-[#D9E7E9] shadow-sm">
                            <img src="{{ asset('icons/Nav Backwards.svg') }}" class="w-5 h-5" alt="Back">
                        </button>
                        <button class="p-1 rounded-lg bg-[#D9E7E9] shadow-sm">
                            <img src="{{ asset('icons/Nav Forward.svg') }}" class="w-5 h-5" alt="Forward">
                        </button>
                    </div>

                    <h1 class="font-semibold text-lg text-center flex-1">
                        @yield('header', 'Evaluasi 1 - (Task Name)')
                    </h1>

                    <div class="px-6"> 
                        <a href="{{ route('peserta.evaluasi1') }}" 
                            class="bg-[#F26E22] text-white text-sm px-3 py-1 rounded-lg font-semibold hover:bg-[#d65c1c] transition whitespace-nowrap">
                            Selesaikan
                        </a>
                    </div>
                </div>

                {{-- Isi Konten --}}
                <div class="bg-white rounded-xl shadow-sm p-6 flex-1 overflow-y-auto">
                    <div class="overflow-x-auto">
                        <table class="evaluation-table w-full border-collapse border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">NO</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">OBJEK PENGEMATAN</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">INDIKATOR MAKS</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">SKALA</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">INDIKATOR MIN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 text-center font-semibold">1</td>
                                    <td class="border border-gray-300">Tujuan pelaksanaan pelatihan</td>
                                    <td class="border border-gray-300 text-center">Tercapai</td>
                                    <td class="border border-gray-300">
                                        <div class="flex justify-center gap-2">
                                            <label><input type="radio" name="scale1" value="1"></label>
                                            <label><input type="radio" name="scale1" value="2"></label>
                                            <label><input type="radio" name="scale1" value="3"></label>
                                            <label><input type="radio" name="scale1" value="4"></label>
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 text-center">Tidak Tercapai</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @yield('content')
                </div>
            </div>

            {{-- Pagination Card - Sekarang di bagian bawah --}}
            <div class="flex items-center justify-center bg-[#F3F3F3] rounded-xl shadow-sm mx-6 mb-3">
                <div class="flex items-center gap-3 px-6 py-2">
                    <button class="p-2 rounded-lg bg-[#D9E7E9] shadow-sm disabled:opacity-50" disabled>
                        <img src="{{ asset('icons/Nav Backwards.svg') }}" class="w-4 h-4" alt="Previous">
                    </button>
                    <span class="text-sm text-gray-600 font-medium">Halaman 1 dari 1</span>
                    <button class="p-2 rounded-lg bg-[#D9E7E9] shadow-sm disabled:opacity-50" disabled>
                        <img src="{{ asset('icons/Nav Forward.svg') }}" class="w-4 h-4" alt="Next">
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/alpinejs" defer></script>
</body>
</html>