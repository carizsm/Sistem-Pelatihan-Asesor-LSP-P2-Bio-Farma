@extends('layouts.admin')

@section('title', 'Daftar Analisis Evaluasi 1')
@section('page_title', 'Evaluasi 1')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Analisis Evaluasi 1</h1>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Pelatihan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Peserta
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
                            {{ $tna->name ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            {{ $tna->start_date ? \Carbon\Carbon::parse($tna->start_date)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            {{ $tna->registrations_count }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-left">
                            <a href="{{ route('admin.feedback_results.show', $tna->id) }}" class="inline-block bg-orange-500 text-white text-sm font-semibold py-1.5 px-4 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                                Lihat Rekap
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="hover:bg-gray-50">
                        <td colspan="4" class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            Tidak ada data TNA yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TAMBAHAN: Bagian Pagination --}}
        <div class="p-4">
            {{ $tnas->links() }}
        </div>
        
    </div>
@endsection