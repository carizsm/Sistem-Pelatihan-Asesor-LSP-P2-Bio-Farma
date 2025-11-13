@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Daftar TNA')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'TNA')

{{-- Konten utama halaman --}}
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar TNA</h1>
        
        <a href="{{ route('admin.tnas.create') }}" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-700 transition duration-200">
            Tambah Data TNA
        </a>
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 border border-green-300 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nomor TNA
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Pelatihan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Pembuat
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Status Realisasi
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-center text-xs font-semibold text-white uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tnas as $tna)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{-- REVISI: Menggunakan $tna->tna_code --}}
                            <a href="{{ route('admin.tnas.show', $tna->id) }}" class="text-blue-600 hover:underline font-semibold">
                                {{ $tna->tna_code }}
                            </a>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{-- REVISI: Menggunakan $tna->name --}}
                            {{ $tna->name }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{ $tna->user->name ?? 'N/A' }}
                        </td>
                         <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $tna->realization_status == 'Terealisasi' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $tna->realization_status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                            
                            <a href="{{ route('admin.tnas.edit', $tna->id) }}" class="text-orange-500 hover:text-orange-700 mx-1">
                                <img src="{{ asset('icons/Button Edit.svg') }}" class="w-8 h-8 inline" alt="Edit">
                            </a>
                            
                            <form action="{{ route('admin.tnas.destroy', $tna->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus TNA ini?');">
                                @csrf
                                @method('DELETE')
                                <button typeM="submit" class="text-red-500 hover:text-red-700 mx-1 align-middle">
                                    <img src="{{ asset('icons/Button Trash.svg') }}" class="w-8 h-8 inline" alt="Delete">
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="hover:bg-gray-50">
                        <td colspan="5" class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            Tidak ada data TNA ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $tnas->links() }}
        </div>
    </div>
@endsection