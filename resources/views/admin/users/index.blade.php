@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Daftar Asesor')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Asesor')

{{-- Konten utama halaman --}}
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Asesor</h1>
        
        <a href="{{ route('admin.users.create') }}" 
           class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-700 transition duration-200">
            Tambah Data Asesor
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
            <div class="mb-4 p-4 rounded bg-green-100 border border-green-300 text-green-800">
                {{ session('success') }}
            </div>
        @endif

    {{-- Tabel Daftar Asesor --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="sticky top-0 z-10 px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            NIK
                        </th>
                        <th class="sticky top-0 z-10 px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Asesor
                        </th>
                        <th class="sticky top-0 z-10 px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Jabatan
                        </th>
                        <th class="sticky top-0 z-10 px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Unit Kerja
                        </th>
                        <th class="sticky top-0 z-10 px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Menggunakan data dinamis dari UserController --}}
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            {{-- Data disesuaikan dengan field dari UserController --}}
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $user->nik }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $user->name }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $user->position->position_name ?? 'N/A' }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $user->unit->unit_name ?? 'N/A' }}</td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-orange-500 hover:text-orange-700 mx-1">
                                    <img src="{{ asset('icons/Button Edit.svg') }}" class="w-8 h-8 inline" alt="Edit">
                                </a>
                                
                                {{-- Tombol Hapus (Form) --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus asesor ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mx-1 align-middle">
                                        <img src="{{ asset('icons/Button Trash.svg') }}" class="w-8 h-8 inline" alt="Delete">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                         <tr class="hover:bg-gray-50">
                            <td colspan="5" class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                Tidak ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- REVISI: Menambahkan Navigasi Pagination --}}
        <div class="p-4">
            {{ $users->links() }}
        </div>

    </div>
@endsection