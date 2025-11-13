@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Kelola Soal Quiz')

{{-- Judul yang tampil di navbar atas (Dinamis) --}}
@section('page_title', 'Atur Soal Quiz - Kelola Soal (' . $tna->name . ')')

{{-- Konten utama halaman --}}
@section('content')
    
    {{-- REVISI: Mengubah max-w-xl menjadi max-w-3xl --}}
    @if(session('success'))
        <div class="max-w-3xl mx-auto mb-4 p-4 rounded bg-green-100 border border-green-300 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-3xl mx-auto mb-4 p-4 rounded bg-red-100 border border-red-300 text-red-800">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="max-w-3xl mx-auto mb-4 p-4 rounded bg-red-100 border border-red-300 text-red-800">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- REVISI: Mengubah max-w-xl menjadi max-w-3xl --}}
    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Form Unggah Soal Pelatihan</h1>
            <p class="text-lg font-semibold text-gray-700 mb-8 text-center">Nama Pelatihan: {{ $tna->name }}</p>

            <form action="{{ route('admin.quiz_questions.import', $tna->id) }}" method="POST" enctype="multipart/form-data">
                @csrf 

                {{-- Langkah 1: Unduh Template --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 1: Unduh template untuk memastikan format file sudah benar</p>
                    <a href="{{ route('admin.quiz_questions.downloadTemplate') }}" class="inline-block bg-sky-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-sky-700 transition duration-200">
                        Unduh Template Kosong
                    </a>
                </div>

                {{-- Langkah 2: Pilih File --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 2: Pilih file Excel yang sudah diisi</p>
                    <input type="file" id="excel_file" name="excel_file" class="hidden" accept=".xlsx, .xls" required>
                    <label for="excel_file" id="file_label" class="cursor-pointer inline-block bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition duration-200">
                        Pilih File
                    </label>
                    <p id="file_name" class="mt-2 text-sm text-gray-600 italic">Belum ada file yang dipilih.</p>
                </div>

                {{-- Langkah 3: Unggah dan Proses --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 3: Proses file yang sudah dipilih</p>
                    <button type="submit" class="bg-orange-500 text-white font-semibold py-2 px-6 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                        Unggah dan Proses
                    </button>
                </div>
            </form>

            {{-- Tombol Kembali --}}
            <div class="flex justify-center mt-10">
                <a href="{{ route('admin.quiz_questions.index') }}" class="px-8 py-3 text-lg border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-400 hover:bg-gray-500 transition duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>
    
    {{-- Menampilkan daftar soal ($questions) yang sudah ada --}}
    @if($questions->count() > 0)
    {{-- REVISI: Mengubah max-w-xl menjadi max-w-3xl --}}
    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">Daftar Soal yang Sudah Diunggah ({{ $questions->count() }})</h2>
            <div class="space-y-4">
                @foreach ($questions as $question)
                <div class="border rounded-lg p-4">
                    <p class="font-semibold text-gray-800">{{ $question->question_number }}. {{ $question->question }}</p>
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

    {{-- Script untuk menampilkan nama file yang dipilih --}}
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
    </script>
@endsection