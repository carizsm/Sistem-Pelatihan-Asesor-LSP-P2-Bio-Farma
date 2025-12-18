<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - LSP Farmasi</title>
    
    @vite('resources/css/app.css')

    <style>
        /* Gaya Default untuk semua link pagination (sebelum diklik/aktif) 
           Warna background: Oranye Tua (#EA580C / bg-orange-600)
           Warna teks: Putih
        */
        nav[role="navigation"] a,
        nav[role="navigation"] span[aria-disabled="true"] {
            background-color: #EA580C !important; /* Oranye Tua */
            border-color: #EA580C !important;
            color: white !important;
        }

        /* Gaya untuk Halaman Aktif (halaman yang sedang dipilih)
           Warna background: Oranye Muda (#F97316 / bg-orange-500)
           Warna teks: Putih
        */
        nav[role="navigation"] span[aria-current="page"] > span {
            background-color: #F97316 !important; /* Oranye Muda */
            border-color: #F97316 !important;
            color: white !important;
        }

        /* Gaya Hover (saat kursor diarahkan)
           Warna background: Oranye Muda (#F97316) - Memberikan efek interaktif
        */
        nav[role="navigation"] a:hover {
            background-color: #F97316 !important; /* Oranye Muda */
            border-color: #F97316 !important;
            color: white !important;
        }

        /* Mengubah warna ring fokus (saat diklik/tab) */
        nav[role="navigation"] a:focus,
        nav[role="navigation"] button:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.5) !important;
            border-color: #F97316 !important;
        }

        /* Ikon panah (SVG) di dalam tombol Next/Prev agar tetap putih */
        nav[role="navigation"] svg {
            color: white !important; 
            fill: currentColor;
        }
        
        /* Menangani tombol disabled (seperti Prev di halaman 1) agar tetap terlihat tapi pudar */
        nav[role="navigation"] span[aria-disabled="true"] {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
    
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

    {{-- Toast Notification Component --}}
    <div x-data="toastManager()" 
         x-init="@if(session('success')) showToast('{{ session('success') }}', 'success') @endif
                @if(session('error')) showToast('{{ session('error') }}', 'error') @endif
                @if(session('info')) showToast('{{ session('info') }}', 'info') @endif
                @if(session('participant_success')) showToast('{{ session('participant_success') }}', 'success') @endif
                @if(session('participant_error')) showToast('{{ session('participant_error') }}', 'error') @endif"
         class="fixed top-4 right-4 z-50 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show"
                 x-transition:enter="transform ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transform ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="flex items-start gap-3 p-4 rounded-lg shadow-lg max-w-md"
                 :class="{
                     'bg-green-100 border-l-4 border-green-500': toast.type === 'success',
                     'bg-red-100 border-l-4 border-red-500': toast.type === 'error',
                     'bg-blue-100 border-l-4 border-blue-500': toast.type === 'info'
                 }">
                <div class="flex-shrink-0">
                    <svg x-show="toast.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="toast.type === 'error'" class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="toast.type === 'info'" class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold" 
                       :class="{
                           'text-green-800': toast.type === 'success',
                           'text-red-800': toast.type === 'error',
                           'text-blue-800': toast.type === 'info'
                       }" 
                       x-text="toast.message"></p>
                </div>
                <button @click="removeToast(toast.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        function toastManager() {
            return {
                toasts: [],
                nextId: 1,
                
                showToast(message, type = 'info') {
                    const id = this.nextId++;
                    const toast = { id, message, type, show: true };
                    this.toasts.push(toast);
                    
                    setTimeout(() => {
                        this.removeToast(id);
                    }, 5000);
                },
                
                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].show = false;
                        setTimeout(() => {
                            this.toasts.splice(index, 1);
                        }, 300);
                    }
                }
            }
        }

        // Auto save ke storage brwoser
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[data-autosave]');

            forms.forEach(form => {
                const formId = form.getAttribute('data-autosave');
                const storageKey = 'autosave_' + formId;
                
                // Kembalikan data saat halaman dibuka
                const savedData = JSON.parse(localStorage.getItem(storageKey)) || {};

                // Loop semua input di dalam form
                form.querySelectorAll('input, textarea, select').forEach(input => {
                    if (input.type === 'file' || input.type === 'password' || input.type === 'submit') return;
                    const name = input.name;
                    if (!name) return;

                    // Restore value jika ada di storage
                    if (savedData[name] !== undefined) {
                        if (input.type === 'radio' || input.type === 'checkbox') {
                            if (input.value === savedData[name]) {
                                input.checked = true;
                            }
                        } else {
                            input.value = savedData[name];
                        }
                    }

                    // Simpan saat user mengubah
                    input.addEventListener('input', function() {
                        let val = this.value;

                        if (this.type === 'checkbox' && !this.checked) val = ''; // Handle uncheck
                        
                        savedData[name] = val;
                        localStorage.setItem(storageKey, JSON.stringify(savedData));
                    });
                });

                // Hapus data saat form sukses disubmit
                form.addEventListener('submit', function() {
                    localStorage.removeItem(storageKey);
                });
            });
        });
    </script>
</body>
</html>