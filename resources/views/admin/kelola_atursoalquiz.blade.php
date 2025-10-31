@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Kelola Soal Quiz')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Atur Soal Quiz - Kelola Soal (Microsoft Excel)')

{{-- Konten utama halaman --}}
@section('content')
    
    @php
        // Data Dummy untuk Halaman Kelola
        $namaPelatihan = 'Microsoft Excel';
    @endphp

    <div class="max-w-xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Form Unggah Soal Pelatihan</h1>
            <p class="text-lg font-semibold text-gray-700 mb-8 text-center">Nama Pelatihan: **{{ $namaPelatihan }}**</p>

            {{-- Form Unggah Soal --}}
            {{-- Action dinonaktifkan sementara --}}
            <form action="#" method="POST" onsubmit="alert('Fungsi Unggah dinonaktifkan sementara.'); return false;" enctype="multipart/form-data">
                @csrf 

                {{-- Langkah 1: Unduh Template --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 1: Unduh template untuk memastikan format file sudah benar</p>
                    {{-- Tombol Unduh Template (Ganti '#' dengan route download template) --}}
                    <a href="#" class="inline-block bg-sky-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-sky-700 transition duration-200">
                        Unduh Template Kosong
                    </a>
                </div>

                {{-- Langkah 2: Pilih File --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 2: Pilih file Excel yang sudah diisi</p>
                    <input type="file" id="quiz_file" name="quiz_file" class="hidden" accept=".xlsx, .xls" required>
                    <label for="quiz_file" id="file_label" class="cursor-pointer inline-block bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition duration-200">
                        Pilih File
                    </label>
                    <p id="file_name" class="mt-2 text-sm text-gray-600 italic">Belum ada file yang dipilih.</p>
                </div>

                {{-- Langkah 3: Unggah dan Proses --}}
                <div class="mb-8 p-4 bg-lsp-bg rounded-lg">
                    <p class="text-md font-semibold text-gray-700 mb-2">Langkah 3: Proses file yang sudah dipilih</p>
                    <button type="submit" class="bg-lsp-primary text-white font-semibold py-2 px-6 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                        Unggah dan Proses
                    </button>
                </div>
            </form>

            {{-- Tombol Kembali --}}
            <div class="flex justify-center mt-10">
                <a href="{{ url('admin/atur-soal-quiz') }}" class="px-8 py-3 text-lg border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-400 hover:bg-gray-500 transition duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>
    
    {{-- Script untuk menampilkan nama file yang dipilih --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('quiz_file');
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