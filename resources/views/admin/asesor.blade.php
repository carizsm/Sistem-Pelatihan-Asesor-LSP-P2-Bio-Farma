@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Daftar Asesor')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Asesor')

{{-- Konten utama halaman --}}
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Asesor</h1>
        {{-- Tombol untuk Tambah Data Asesor. Gunakan route sementara --}}
        <a href="{{ url('admin/asesor/tambah') }}" class="bg-lsp-green text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-700 transition duration-200">
            Tambah Data Asesor
        </a>
    </div>

    {{-- Tabel Daftar Asesor --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider rounded-tl-lg">
                            NIK
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Asesor
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Jabatan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Unit Kerja
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider rounded-tr-lg">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Baris data statis sesuai gambar --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">6821408</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">Jaenudin</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">Pelaksana</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">Produksi Vaksin Virus</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{-- Tombol Edit. Gunakan route sementara --}}
                            <a href="{{ url('admin/asesor/ubah/6821408') }}" class="text-orange-500 hover:text-orange-700 mx-1">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            {{-- Tombol Hapus --}}
                            <button type="button" onclick="alert('Fungsi Hapus dinonaktifkan sementara.')" class="text-red-500 hover:text-red-700 mx-1">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    {{-- Anda bisa menambahkan baris data statis lain di sini --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection