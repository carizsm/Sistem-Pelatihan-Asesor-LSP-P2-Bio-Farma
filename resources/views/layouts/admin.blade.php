<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - LSP Holding BUMN Farmasi</title>

    {{-- Menggunakan Tailwind CSS dari CDN untuk kemudahan --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Kustomisasi warna sesuai desain Anda --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'lsp-primary': '#F47C45', // Oranye utama
                        'lsp-secondary': '#FEF3ED', // Latar belakang nav-link aktif
                        'lsp-green': '#198754',   // Tombol hijau
                        'lsp-bg': '#FBF7F4',      // Latar belakang konten
                    }
                }
            }
        }
    </script>

    {{-- Menambahkan library ikon (Heroicons) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/2.1.3/24/outline/heroicons.min.css">
    
    <style>
        /* Gaya khusus untuk sidebar-link aktif */
        .nav-active {
            background-color: #FEF3ED; /* lsp-secondary */
            border-left: 4px solid #F47C45; /* lsp-primary */
            color: #F47C45;
            font-weight: 600;
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem; /* py-3 px-6 */
            color: #4B5563; /* text-gray-600 */
            transition: all 0.2s;
        }
        .nav-link:hover {
            background-color: #F3F4F6; /* gray-100 */
        }
        .nav-active .nav-icon, .nav-link:hover .nav-icon {
             color: #F47C45;
        }
        .nav-icon {
            width: 1.5rem; /* w-6 */
            height: 1.5rem; /* h-6 */
            margin-right: 0.75rem; /* mr-3 */
            color: #6B7280; /* text-gray-500 */
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen bg-lsp-bg">
    <aside class="w-64 bg-white shadow-lg flex flex-col justify-between">
        <div>
            <div class="p-6 flex justify-center">
                <img src="{{ asset('images/logo-lsp.svg') }}" alt="LSP Logo" class="h-12">
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.asesor') }}" class="nav-link {{ request()->is('admin/asesor') ? 'nav-active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path></svg>
                    <span>Asesor</span>
                </a>
                <a href="{{ route('admin.tna') }}" class="nav-link {{ request()->is('admin/tna') ? 'nav-active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path></svg>
                    <span>TNA</span>
                </a>
                <a href="{{ route('admin.evaluasi1') }}" class="nav-link {{ request()->is('admin/evaluasi-1') ? 'nav-active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path></svg>
                    <span>Evaluasi 1</span>
                </a>
                <a href="{{ route('admin.evaluasi2') }}" class="nav-link {{ request()->is('admin/evaluasi-2') ? 'nav-active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12"></path></svg>
                    <span>Evaluasi 2</span>
                </a>
                 <a href="{{ route('admin.atursoalquiz') }}" class="nav-link {{ request()->is('admin/atur-soal-quiz') ? 'nav-active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"></path></svg>
                    <span>Atur Soal Quiz</span>
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-gray-200 relative">
            
            <div id="logout-button-container" class="hidden absolute bottom-full mb-2 w-full px-4">
                
                {{-- Ini adalah cara standar & aman untuk logout di Laravel --}}
                <form method="POST" action="{{-- route('logout') --}}">
                    @csrf
                    <a href="{{-- route('logout') --}}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block w-full text-center bg-red-500 text-white py-2 px-3 rounded-lg shadow font-semibold hover:bg-red-600 transition duration-200 text-sm">
                        Logout
                    </a>
                </form>

            </div>

            <button id="user-menu-trigger" type="button" class="flex items-center w-full text-left focus:outline-none transition duration-200 hover:bg-gray-100 p-2 rounded-lg">
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center font-bold text-gray-600 flex-shrink-0">
                    A
                </div>
                <div class="ml-3">
                    <p class="font-semibold text-sm text-gray-800">Admin123</p>
                    <p class="text-xs text-gray-500">Admin</p>
                </div>
            </button>

        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm flex justify-between items-center px-6 py-4">
             <div class="flex items-center space-x-2">
                 <button class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                 </button>
                 <button class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                 </button>
             </div>
             <h2 class="font-semibold text-xl text-gray-700">@yield('page_title')</h2>
             {{-- Bisa ditambahkan item lain di navbar kanan jika perlu --}}
             <div></div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-lsp-bg p-8">
            
            {{-- Konten utama dari setiap halaman akan dimuat di sini --}}
            @yield('content')

        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userMenuTrigger = document.getElementById('user-menu-trigger');
        const logoutContainer = document.getElementById('logout-button-container');

        userMenuTrigger.addEventListener('click', (event) => {
            event.stopPropagation(); 
            logoutContainer.classList.toggle('hidden');
        });

        window.addEventListener('click', (event) => {
            if (!userMenuTrigger.contains(event.target) && !logoutContainer.contains(event.target)) {
                logoutContainer.classList.add('hidden');
            }
        });
    });
</script>

</body>
</html>