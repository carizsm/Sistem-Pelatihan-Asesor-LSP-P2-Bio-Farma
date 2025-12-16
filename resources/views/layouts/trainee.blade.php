<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Peserta') - LSP Farmasi</title>
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
                              {{ request()->routeIs('peserta.evaluasi1') || request()->routeIs('evaluasi1.form') || request()->routeIs('evaluasi1.review') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="$store.sidebar.open">Evaluasi 1</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi2') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                              {{ request()->routeIs('peserta.evaluasi2') || request()->routeIs('evaluasi2.quiz.form') || request()->routeIs('evaluasi2.review') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
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
                @yield('page_title', 'Dashboard Peserta') 
            </h1> 
        </div>

        {{-- Area Konten Utama --}}
        <div class="mt-3">
            @yield('content')
        </div>
    </main>

    {{-- Toast Notification Component --}}
    @include('components.toast-notification')

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

    @stack('scripts')
</body>
</html>
