<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Evaluasi 2')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#EEE8E5] font-sans text-gray-800 flex min-h-screen">

    {{-- Sidebar --}}
    <aside 
        x-data="{ open: true }" 
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
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('dashboard') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/dashboard.svg') }}" class="w-7 h-7 shrink-0" alt="Dashboard">
                        <span x-show="open">Dashboard</span>
                    </a>
                </li>

                <!-- Presensi -->
                <li>
                    <a href="{{ route('peserta.presensi') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.presensi') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/presensi.svg') }}" class="w-7 h-7 shrink-0" alt="Presensi">
                        <span x-show="open">Presensi</span>
                    </a>
                </li>

                <!-- Evaluasi 1 -->
                <li>
                    <a href="{{ route('peserta.evaluasi1') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.evaluasi1') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="open">Evaluasi 1</span>
                    </a>
                </li>

                <!-- Evaluasi 2 -->
                <li>
                    <a href="{{ route('peserta.evaluasi2') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.evaluasi2') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 2">
                        <span x-show="open">Evaluasi 2</span>
                    </a>
                </li>
            </nav>
        </div>

        {{-- Profil + Logout (Bagian bawah sidebar) --}}
        <div class="flex items-center justify-between p-4 border-t border-gray-300 hover:bg-gray-100 transition">
            {{-- Profil --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('icons/Avatar.svg') }}" class="w-9 h-9 shrink-0 bg-[#D9E7E9] rounded-lg" alt="User">
                <div x-show="open">
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">Peserta</p>
                </div>
            </div>

            {{-- Tombol Logout --}}
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
    <main class="flex-1 px-6 pb-6 pt-2">

        {{-- Navbar Atas --}}
        <div class="flex items-center bg-[#F3F3F3] rounded-xl p-2 shadow-sm mb-3 relative mt-0 px-6"> 
            <div class="flex items-center gap-3"> 
                <button class="p-1 rounded-lg bg-[#D9E7E9] shadow-sm"> 
                    <img src="{{ asset('icons/Nav Backwards.svg') }}" class="w-5 h-5" alt="Back"> 
                </button> 
                <button class="p-1 rounded-lg bg-[#D9E7E9] shadow-sm"> 
                    <img src="{{ asset('icons/Nav Forward.svg') }}" class="w-5 h-5" alt="Forward"> 
                </button> 
            </div> 
            <h1 class="absolute left-1/2 -translate-x-1/2 font-semibold text-lg"> 
                @yield('header', 'Evaluasi 2') 
            </h1> 
        </div>
        {{-- Isi Konten --}}
        <div class="mt-3"> 
            <h2 class="text-xl font-semibold mb-4">Daftar Evaluasi 2</h2> 
            <p class="text-sm text-gray-600 mb-4">Daftar tugas yang tersedia</p>
            {{-- Card tugas placeholder --}} 
            <div class="bg-[#F3F3F3] rounded-xl shadow-sm p-6 space-y-4"> 
                {{-- card tugas dummy--}}
                <div class="card-content bg-white rounded-lg shadow-sm px-5 py-3 flex justify-between items-center border border-gray-200">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-10 h-10 bg-[#E6F4F1] rounded-md shrink-0">
                            <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-10 h-10" alt="Tugas Icon">
                        </div>
                        <!-- Info tugas -->
                        <div class="card-text flex flex-col justify-center">
                            <p class="text-sm text-gray-500 leading-none">Evaluasi 2</p>
                            <h3 class="font-semibold text-gray-900 text-base leading-tight mt-1">Tugas Dummy</h3>
                        </div>
                    </div>
                    <!-- Tombol Kerjakan -->
                    <a href="{{ route('peserta.quiz') }}" 
                       class="card-button bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm font-semibold px-4 py-2 rounded-md transition shrink-0">
                       Kerjakan
                    </a>
                </div>
            </div>
        </div>

        @yield('content')
    </main>
     

    <script src="https://unpkg.com/alpinejs" defer></script>
</body>
</html>