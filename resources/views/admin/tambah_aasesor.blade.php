@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Tambah Data Asesor')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Asesor - Tambah Data Asesor')

{{-- Konten utama halaman --}}
@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">Tambah Data Asesor</h2>
            
            {{-- Form dengan action sementara '#' --}}
            <form action="#" method="POST" onsubmit="alert('Data tidak disimpan ke database karena koneksi dinonaktifkan sementara.'); return false;">
                @csrf 

                {{-- Nama Asesor --}}
                <div class="mb-4">
                    <label for="nama_asesor" class="block text-sm font-medium text-gray-700 mb-1">Nama Asesor</label>
                    <input type="text" name="nama_asesor" id="nama_asesor" value="Jaenudin" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800"
                           placeholder="Masukkan Nama Asesor" required>
                </div>

                {{-- NIK Asesor --}}
                <div class="mb-4">
                    <label for="nik_asesor" class="block text-sm font-medium text-gray-700 mb-1">NIK Asesor</label>
                    <input type="text" name="nik_asesor" id="nik_asesor" value="6821408" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800"
                           placeholder="Masukkan NIK Asesor" required>
                </div>

                {{-- Jabatan --}}
                <div class="mb-4">
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <select name="jabatan" id="jabatan" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800 appearance-none" required>
                        <option value="">Pilih Jabatan</option>
                        <option value="Pelaksana" selected>Pelaksana</option>
                        <option value="Supervisor">Supervisor</option>
                    </select>
                </div>

                {{-- Unit Kerja --}}
                <div class="mb-4">
                    <label for="unit_kerja" class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                    <select name="unit_kerja" id="unit_kerja" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800 appearance-none" required>
                        <option value="">Pilih Unit Kerja</option>
                        <option value="Produksi Vaksin Virus" selected>Produksi Vaksin Virus</option>
                        <option value="Quality Control">Quality Control</option>
                    </select>
                </div>

                {{-- Checkbox Pembuat Asesor --}}
                <div class="mb-6">
                    <div class="flex items-center">
                        <input id="pembuat_asesor" name="pembuat_asesor" type="checkbox" checked
                               class="h-4 w-4 text-lsp-primary border-gray-300 rounded focus:ring-lsp-primary">
                        <label for="pembuat_asesor" class="ml-2 block text-sm text-gray-900">
                            Pembuat Asesor
                        </label>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-center space-x-4">
                    {{-- Tombol Kembali mengarah ke halaman daftar asesor --}}
                    <a href="{{ url('admin/asesor') }}" class="px-6 py-2 border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-300 hover:bg-gray-400 transition duration-200">
                        Kembali
                    </a>
                    <button type="submit" class="px-6 py-2 bg-lsp-green text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection