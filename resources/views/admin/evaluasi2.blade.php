@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Daftar Analisis Evaluasi 2')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Evaluasi 2')

{{-- Konten utama halaman --}}
@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Analisis Evaluasi 2</h1>

    {{-- Tabel Daftar Pelatihan Evaluasi 2 --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider rounded-tl-lg">
                            Nama Pelatihan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Peserta
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Pre-Test
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Post-Test
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Selesai
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
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">2</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">40,0</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">95,0</td>
                         <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">2</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                            {{-- Tombol Lihat Rekap (Link ke halaman rincian skor) --}}
                            {{-- Ganti '#' dengan route yang sesuai --}}
                            <a href="{{ url('admin/evaluasi-2/rincian/microsoft-excel') }}" class="inline-block bg-lsp-primary text-white text-sm font-semibold py-2 px-4 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                                Lihat Rekap
                            </a>
                        </td>
                    </tr>
                    {{-- Tambahkan baris dummy lain di sini jika perlu --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection