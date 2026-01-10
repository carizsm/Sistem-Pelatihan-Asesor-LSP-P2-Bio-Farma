@extends('layouts.admin')

@section('title', 'Daftar Soal Pelatihan')
@section('page_title', 'Atur Soal Quiz')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Soal Pelatihan</h1>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Pelatihan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Status Soal
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Jumlah Soal
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tnas as $tna)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            {{-- REVISI: Menggunakan $tna->name --}}
                            {{ $tna->name ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            @if($tna->quiz_questions_count > 0)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    Sudah Ada
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    Belum Ada
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            {{ $tna->quiz_questions_count }} Soal
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-left">
                            <a href="{{ route('admin.quiz_questions.show', $tna->id) }}" class="inline-block bg-orange-500 text-white text-sm font-semibold py-1.5 px-4 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                                Kelola Soal
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="hover:bg-gray-50">
                        <td colspan="4" class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            Tidak ada data TNA ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        <div class="p-4">
            {{ $tnas->links() }}
        </div>
    </div>
@endsection