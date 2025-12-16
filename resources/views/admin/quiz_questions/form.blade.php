@extends('layouts.admin')

@section('title', 'Kelola Soal Quiz')
@section('page_title', 'Atur Soal Quiz - Kelola Soal (' . $tna->name . ')')

@section('content')
    
    @php
        $isExpired = \Carbon\Carbon::parse($tna->end_date)->isPast();
        $questionCount = $questions->count();
        $hasQuestions = $questionCount > 0;
    @endphp

    {{-- Warning Expired --}}
    @if($isExpired)
        <div class="max-w-3xl mx-auto mb-6 p-4 rounded bg-yellow-100 border border-yellow-300 text-yellow-800 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span><strong>Perhatian:</strong> Pelaksanaan TNA ini sudah berakhir. Anda tidak dapat mengubah atau menghapus soal lagi.</span>
        </div>
    @endif

    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg relative">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Form Unggah Soal Pelatihan</h1>
            <p class="text-lg font-semibold text-gray-700 mb-8 text-center">Nama Pelatihan: {{ $tna->name }}</p>

            {{-- Form Upload --}}
            <form id="uploadForm" action="{{ route('admin.quiz_questions.import', $tna->id) }}" method="POST" enctype="multipart/form-data">
                @csrf 

                {{-- Langkah 1 --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 1: Unduh template untuk memastikan format file sudah benar</p>
                    <a href="{{ route('admin.quiz_questions.downloadTemplate') }}" class="inline-block w-full sm:w-72 text-center bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-700 transition duration-200">
                        Unduh Template Kosong
                    </a>
                </div>

                {{-- Langkah 2 --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 2: Pilih file Excel yang sudah diisi</p>
                    <input type="file" id="excel_file" name="excel_file" class="hidden" accept=".xlsx, .xls" @if($isExpired) disabled @endif required>
                    
                    <label for="excel_file" id="file_label" class="cursor-pointer inline-block w-full sm:w-72 text-center bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition duration-200 {{ $isExpired ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Pilih File
                    </label>
                    <p id="file_name" class="mt-2 text-sm text-gray-600 italic">Belum ada file yang dipilih.</p>
                </div>

                {{-- Langkah 3 --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 3: Proses file yang sudah dipilih</p>
                    <button type="button" onclick="checkUpload()" class="bg-orange-500 w-full sm:w-72 text-center text-white font-semibold py-2 px-6 rounded-lg shadow hover:bg-orange-600 transition duration-200 {{ $isExpired ? 'opacity-50 cursor-not-allowed' : '' }}" @if($isExpired) disabled @endif>
                        Unggah dan Proses
                    </button>
                </div>
            </form>

            {{-- Area Tombol Bawah --}}
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-10">
                <a href="{{ route('admin.quiz_questions.index') }}" class="w-full sm:w-auto px-8 py-3 text-lg text-center border border-gray-500 text-white font-semibold rounded-lg shadow-sm bg-gray-500 hover:bg-gray-600 transition duration-200">
                    Kembali
                </a>

                @if($hasQuestions)
                    <button type="button" 
                        @if(!$isExpired) onclick="showDeleteModal()" @endif
                        class="w-full sm:w-auto px-8 py-3 text-lg font-semibold rounded-lg shadow-sm transition duration-200 text-white
                        {{ $isExpired ? 'bg-red-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700' }}"
                        {{ $isExpired ? 'disabled' : '' }}>
                        Hapus Semua Soal
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Daftar Soal --}}
    @if($hasQuestions)
    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">Daftar Soal yang Sudah Diunggah ({{ $questionCount }})</h2>
            <div class="space-y-4">
                @foreach ($questions as $question)
                <div class="border rounded-lg p-4 bg-gray-50 relative group">
                    <div class="flex justify-between items-start">
                        <p class="font-semibold text-gray-800 pr-8">{{ $question->question_number }}. {{ $question->question }}</p>
                        
                        @if(!$isExpired)
                        <form action="{{ route('admin.quiz_questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Hapus soal nomor {{ $question->question_number }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="hover:opacity-75 p-1" title="Hapus Soal">
                                <img src="{{ asset('icons/Button Trash.svg') }}" class="w-6 h-6" alt="Hapus">
                            </button>
                        </form>
                        @endif
                    </div>

                    <ul class="list-disc list-inside mt-2 pl-4">
                        @foreach ($question->quizAnswers as $answer)
                            <li class="{{ $answer->is_correct ? 'font-bold text-green-600' : 'text-gray-600' }}">
                                {{ $answer->answer }}
                                @if($answer->is_correct) (Jawaban Benar) @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================== --}}
    {{-- MODAL 1: KONFIRMASI GANTI SOAL (UPLOAD)                        --}}
    {{-- ============================================================== --}}
    <div id="confirmModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md p-6 transform transition-all scale-100">
            <div class="flex justify-center mb-4">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Konfirmasi Penggantian Soal</h3>
            <p class="text-gray-600 text-center mb-6">
                TNA ini sudah memiliki <span class="font-bold text-gray-800">{{ $questionCount }} soal</span>. Mengunggah file baru akan <span class="font-bold text-red-600">MENGGANTI (menghapus)</span> semua soal lama. Yakin ingin lanjut?
            </p>

            <div class="flex justify-center gap-4">
                <button onclick="closeModal('confirmModal')" class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition duration-200">
                    Batal
                </button>
                <button onclick="submitUploadForm()" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-200">
                    Lanjut
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MODAL 2: KONFIRMASI HAPUS SEMUA (DELETE ALL)                   --}}
    {{-- ============================================================== --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md p-6 transform transition-all scale-100">
            <div class="flex justify-center mb-4">
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Hapus Semua Soal?</h3>
            <p class="text-gray-600 text-center mb-6">
                Anda akan menghapus <span class="font-bold text-gray-800">SEMUA soal</span> dalam TNA ini. Tindakan ini tidak dapat dikembalikan.
            </p>

            <div class="flex justify-center gap-4">
                <button onclick="closeModal('deleteModal')" class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition duration-200">
                    Batal
                </button>
                <button onclick="submitDeleteAllForm()" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-200">
                    Ya, Hapus Semua
                </button>
            </div>
        </div>
    </div>

    {{-- Form Tersembunyi untuk Hapus Semua --}}
    @if($hasQuestions && !$isExpired)
    <form id="deleteAllForm" action="{{ route('admin.quiz_questions.destroyAll', $tna->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif

    {{-- Script Logika --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('excel_file');
            const fileNameDisplay = document.getElementById('file_name');

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = 'File terpilih: ' + fileInput.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'Belum ada file yang dipilih.';
                }
            });
        });

        // Logika Upload
        function checkUpload() {
            const hasQuestions = {{ $hasQuestions ? 'true' : 'false' }};
            const fileInput = document.getElementById('excel_file');

            if (fileInput.files.length === 0) {
                alert('Silakan pilih file Excel terlebih dahulu.');
                return;
            }

            if (hasQuestions) {
                document.getElementById('confirmModal').classList.remove('hidden');
            } else {
                document.getElementById('uploadForm').submit();
            }
        }

        // Logika Hapus Semua
        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Fungsi Umum Tutup Modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Submit Form Upload
        function submitUploadForm() {
            document.getElementById('uploadForm').submit();
        }

        // Submit Form Hapus Semua
        function submitDeleteAllForm() {
            document.getElementById('deleteAllForm').submit();
        }
    </script>
@endsection