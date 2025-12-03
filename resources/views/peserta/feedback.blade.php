<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Feedback Evaluasi 1')</title>
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
                        {{ request()->routeIs('peserta.evaluasi1') || request()->routeIs('peserta.feedback') ? 
                            'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
                        <img src="{{ asset('icons/Evaluasi 1.svg') }}" class="w-7 h-7 shrink-0" alt="Evaluasi 1">
                        <span x-show="$store.sidebar.open">Evaluasi 1</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peserta.evaluasi2') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg mx-2 my-1 transition 
                        {{ request()->routeIs('peserta.quiz') || request()->routeIs('peserta.evaluasi2') ? 
                        'bg-[#F4E5DD] text-[#F26E22] font-semibold' : 'hover:bg-gray-200' }}">
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
        class="flex-1 flex flex-col min-h-screen transition-all duration-300"
    >
        <div class="flex-1 px-6 pb-6 pt-2 flex flex-col space-y-3">
            {{-- Navbar Atas --}}
            <div class="flex items-center bg-[#F3F3F3] rounded-xl p-2 shadow-sm mb-0 mt-0">
                {{-- Spacer kiri untuk balance --}}
                <div class="w-32"></div>

                {{-- Title di tengah --}}
                <div class="flex-1 text-center">
                    <h1 class="font-semibold text-lg">
                        Evaluasi 1 - {{ $tna->name }}
                        @if(isset($feedback))
                            <span class="text-lg text-green-600 font-normal">(Review)</span>
                        @endif
                    </h1>
                </div>

                {{-- Button kanan --}}
                <div class="w-32 flex justify-end items-center"> 
                    @if(!isset($feedback))
                        <button type="submit" form="feedback-form"
                            class="inline-flex items-center justify-center bg-[#F26E22] text-white text-sm px-3 h-8 rounded-lg font-semibold hover:bg-[#d65c1c] transition whitespace-nowrap leading-none">
                            Selesaikan
                        </button>
                    @else
                        <a href="{{ route('peserta.evaluasi1') }}"
                           class="inline-flex items-center justify-center bg-gray-500 text-white text-sm px-3 h-8 rounded-lg font-semibold hover:bg-gray-600 transition whitespace-nowrap leading-none">
                            Kembali
                        </a>
                    @endif
                </div>
            </div>

            {{-- Isi Konten --}}
            <div class="bg-white rounded-xl shadow-sm p-6 flex-1 overflow-y-auto">
                <form id="feedback-form" method="POST" action="{{ route('evaluasi1.store', $registration) }}">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="evaluation-table w-full border-collapse border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">NO</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">OBJEK PENGAMATAN</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">INDIKATOR MAKS</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">SKALA</th>
                                    <th class="border border-orange-400 bg-orange-200 px-4 py-3">INDIKATOR MIN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $questions = [
                                        // Tujuan Pelatihan
                                        ['Tujuan pelaksanaan pelatihan', 'Tercapai', 'Tidak Tercapai'],
                                        ['Tujuan Anda mengikuti pelatihan ini', 'Tercapai', 'Tidak Tercapai'],
                                        
                                        // Materi Pelatihan - separator
                                        ['__SEPARATOR__', 'MATERI PELATIHAN', ''],
                                        ['Cakupan materi', 'Sangat Lengkap', 'Tidak Lengkap'],
                                        ['Kedalaman Materi', 'Sangat Dalam', 'Sangat Dangkal'],
                                        ['Daya Tarik Topik', 'Sangat Menarik', 'Tidak Menarik'],
                                        
                                        // Alokasi Waktu - separator
                                        ['__SEPARATOR__', 'ALOKASI WAKTU', ''],
                                        ['Alokasi Waktu Pelaksanaan Pelatihan', 'Panjang', 'Pendek'],
                                        ['Alokasi Waktu Untuk Diskusi', 'Panjang', 'Pendek'],
                                        
                                        // Instruktur - separator
                                        ['__SEPARATOR__', 'INSTRUKTUR', ''],
                                        ['Daya Tarik Penyampaian Topik', 'Sangat Menarik', 'Tidak Menarik'],
                                        ['Penguasaan Atas Materi Pelatihan', 'Sangat Baik', 'Sangat Buruk'],
                                        ['Penyampaian Materi', 'Sistematik', 'Tidak Sistematik'],
                                        ['Kemampuan Menjawab Pertanyaan', 'Sangat Baik', 'Sangat Buruk'],
                                        
                                        // Fasilitas Pelatihan - separator
                                        ['__SEPARATOR__', 'FASILITAS PELATIHAN', ''],
                                        ['Kualitas Tempat Pelatihan', 'Sangat Baik', 'Sangat Buruk'],
                                        ['Kualitas Modul/Handouts', 'Sangat Dalam', 'Sangat Dangkal'],
                                        
                                        // Hasil Pelatihan - separator
                                        ['__SEPARATOR__', 'HASIL PELATIHAN', ''],
                                        ['Manfaat Pelatihan', 'Sangat Bermanfaat', 'Tidak Bermanfaat'],
                                        ['Aplikasi Pada Pekerjaan', 'Aplikatif', 'Tidak Aplikatif'],
                                    ];
                                    
                                    $questionNumber = 0;
                                @endphp

                                @foreach($questions as $index => $question)
                                    @if($question[0] === '__SEPARATOR__')
                                        {{-- Category Separator Row --}}
                                        <tr class="bg-gray-100">
                                            <td colspan="5" class="border border-gray-300 px-4 py-2 text-center font-bold">
                                                {{ $question[1] }}
                                            </td>
                                        </tr>
                                    @else
                                        @php 
                                            $questionNumber++; 
                                            $scoreField = sprintf('score_%02d', $questionNumber);
                                            $savedScore = isset($feedback) ? $feedback->$scoreField : null;
                                        @endphp
                                        <tr class="{{ isset($feedback) ? 'bg-gray-50' : '' }}">
                                            <td class="border border-gray-300 text-center font-semibold">{{ $questionNumber }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $question[0] }}</td>
                                            <td class="border border-gray-300 text-center px-4">{{ $question[1] }}</td>
                                            <td class="border border-gray-300">
                                                <div class="flex justify-center gap-4 py-2">
                                                    @for($i = 4; $i >= 1; $i--)
                                                        <label class="flex items-center gap-1 {{ isset($feedback) ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                                            <input type="radio" 
                                                                   name="score_{{ str_pad($questionNumber, 2, '0', STR_PAD_LEFT) }}" 
                                                                   value="{{ $i }}"
                                                                   {{ isset($feedback) && $savedScore == $i ? 'checked' : '' }}
                                                                   {{ isset($feedback) ? 'disabled' : 'required' }}
                                                                   class="w-4 h-4 {{ isset($feedback) ? 'cursor-not-allowed' : '' }}">
                                                            <span class="text-sm {{ isset($feedback) && $savedScore == $i ? 'font-bold text-[#F26E22]' : '' }}">
                                                                {{ $i }}
                                                            </span>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td class="border border-gray-300 text-center px-4">{{ $question[2] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- HAPUS: Ringkasan skor tidak ditampilkan lagi --}}
                </form>
            </div>
        </div>

        {{-- HAPUS: Pagination Card tidak diperlukan --}}
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

        // Alpine component untuk auto-save feedback
        document.addEventListener('DOMContentLoaded', function() {
            @if(!isset($feedback))
                // MODE PENGISIAN - Enable auto-save
                const form = document.getElementById('feedback-form');
                const registrationId = {{ $registration->id }};
                const storageKey = `feedback_${registrationId}`;

                // Restore saved data dari localStorage
                function restoreSavedData() {
                    const savedData = localStorage.getItem(storageKey);
                    if (savedData) {
                        const answers = JSON.parse(savedData);
                        Object.keys(answers).forEach(scoreField => {
                            const radio = document.querySelector(`input[name="${scoreField}"][value="${answers[scoreField]}"]`);
                            if (radio) {
                                radio.checked = true;
                            }
                        });
                        console.log('✅ Data feedback berhasil di-restore dari localStorage');
                    }
                }

                // Save data ke localStorage setiap ada perubahan
                function saveToLocalStorage() {
                    const formData = new FormData(form);
                    const answers = {};
                    
                    for (let [key, value] of formData.entries()) {
                        if (key.startsWith('score_')) {
                            answers[key] = value;
                        }
                    }
                    
                    localStorage.setItem(storageKey, JSON.stringify(answers));
                    console.log('💾 Data feedback disimpan ke localStorage', answers);
                }

                // Event listener untuk semua radio button
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.addEventListener('change', saveToLocalStorage);
                });

                // Auto-save sebelum page unload
                window.addEventListener('beforeunload', saveToLocalStorage);

                // Submit handler - clear localStorage setelah submit
                form.addEventListener('submit', function() {
                    localStorage.removeItem(storageKey);
                    console.log('🗑️ Data feedback dihapus dari localStorage (submitted)');
                });

                // Restore data saat halaman dimuat
                restoreSavedData();

                // Visual indicator untuk auto-save (optional)
                let saveIndicator = document.createElement('div');
                saveIndicator.id = 'save-indicator';
                saveIndicator.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-300';
                saveIndicator.textContent = '✓ Tersimpan otomatis';
                document.body.appendChild(saveIndicator);

                // Show indicator saat save
                const originalSave = saveToLocalStorage;
                saveToLocalStorage = function() {
                    originalSave();
                    saveIndicator.style.opacity = '1';
                    setTimeout(() => {
                        saveIndicator.style.opacity = '0';
                    }, 2000);
                };
            @endif
        });
    </script>
</body>
</html>