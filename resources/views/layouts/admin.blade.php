<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - LSP Farmasi</title>
    
    @vite('resources/css/app.css')
    
</head>
<body class="bg-[#EEE8E5] font-sans text-gray-800 flex min-h-screen">

    {{-- Sidebar (Tidak berubah) --}}
    <aside 
        x-data="{ open: true }" 
        :class="open ? 'w-64' : 'w-20'" 
        class="bg-[#F3F3F3] h-screen flex flex-col justify-between transition-all duration-300 shadow-sm"
    >
        {{-- Bagian Atas Sidebar --}}
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

            {{-- Menu Navigasi Admin --}}
            <nav class="flex flex-col gap-2 mt-4">
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                             {{ request()->routeIs('admin.users*') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Asesor.svg') }}" class="w-7 h-7 shrink-0" alt="Asesor">
                        <span x-show="open">Asesor</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tnas.index') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                             {{ request()->routeIs('admin.tnas*') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/TNA.svg') }}" class="w-7 h-7 shrink-0" alt="TNA">
                        <span x-show="open">TNA</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.feedback_results.index') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                             {{ request()->routeIs('admin.feedback_results*') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="open">Evaluasi 1</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.quiz_results.index') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                             {{ request()->routeIs('admin.quiz_results*') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 2.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 2">
                        <span x-show="open">Evaluasi 2</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.quiz_questions.index') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                             {{ request()->routeIs('admin.quiz_questions*') ? 'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Atur Soal Quiz.svg') }}" class="w-7 h-7 shrink-0" alt="Atur Soal Quiz">
                        <span x-show="open">Atur Soal Quiz</span>
                    </a>
                </li>
            </nav>
        </div>

        {{-- Profil Admin + Logout --}}
        <div class="flex items-center justify-between p-4 border-t border-gray-300 hover:bg-gray-100 transition">
            <div class="flex items-center gap-3">
                <img src="{{ asset('icons/Avatar.svg') }}" class="w-9 h-9 shrink-0 bg-[#D9E7E9] rounded-lg" alt="User">
                <div x-show="open">
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'Jaenudin' }}</p>
                    <p class="text-xs text-gray-500">Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-show="open">
                @csrf
                <button type="submit" 
                   class="text-red-500 hover:text-red-700 transition" 
                   title="Logout">
                    <img src="{{ asset('icons/Logout.svg') }}" class="w-6 h-6" alt="Logout">
                </button>
            </form>
        </div>
        
    </aside>

    {{-- Konten Utama --}}
    <main class="flex-1 px-6 pb-6 pt-2 h-screen flex flex-col">

        {{-- Navbar Atas (Header Konten) --}}
        <div class="flex items-center justify-center bg-[#F3F3F3] rounded-xl p-3 shadow-sm mb-3 relative mt-0 px-6 shrink-0 h-14"> 
            
            {{-- REVISI: Tombol Back & Forward dihapus --}}
            
            <h1 class="font-semibold text-lg text-gray-800"> 
                @yield('page_title', 'Admin Dashboard') 
            </h1> 
        </div>

        {{-- Area Konten Utama --}}
        <div class="mt-4 flex-1 overflow-y-auto">
            @yield('content')
        </div>
        
    </main>

    <script src="https://unpkg.com/alpinejs" defer></script>
</body>
</html>