@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Daftar Soal Pelatihan')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Atur Soal Quiz')

{{-- Konten utama halaman --}}
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Soal Pelatihan</h1>

    {{-- Tabel Daftar Pelatihan --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider rounded-tl-lg">
                            Nama Pelatihan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Status Soal
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Tanggal Unggah
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-center text-xs font-semibold text-white uppercase tracking-wider rounded-tr-lg">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Baris Contoh Data (Dummy) --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">Microsoft Excel</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">Sudah Ada</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">21/09/2025</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                            {{-- Tombol Kelola Soal (Link ke halaman unggah) --}}
                            {{-- Ganti '#' dengan route yang sesuai --}}
                            <a href="{{ url('admin/atur-soal-quiz/kelola/microsoft-excel') }}" class="inline-block bg-lsp-primary text-white text-sm font-semibold py-2 px-4 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                                Kelola Soal
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">Komunikasi Efektif</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">Belum Ada</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">-</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                            <a href="{{ url('admin/atur-soal-quiz/kelola/komunikasi-efektif') }}" class="inline-block bg-lsp-primary text-white text-sm font-semibold py-2 px-4 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                                Kelola Soal
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection