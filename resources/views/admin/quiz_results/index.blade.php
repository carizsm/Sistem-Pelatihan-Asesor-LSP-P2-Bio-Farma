@extends('layouts.admin')

@section('title', 'Daftar Analisis Evaluasi 2')
@section('page_title', 'Evaluasi 2')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Analisis Evaluasi 2</h1>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Nama Pelatihan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Peserta
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Pre-Test (Rata-rata)
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Post-Test (Rata-rata)
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Selesai (Peserta)
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-orange-500 text-center text-xs font-semibold text-white uppercase tracking-wider">
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
                            {{ $tna->registrations_count }}
                        </td>
                        
                        {{-- REVISI: Ganti 'N/A' dengan data dinamis --}}
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            {{ number_format($tna->avg_pre_test ?? 0, 2) }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            {{ number_format($tna->avg_post_test ?? 0, 2) }}
                        </td>
                         <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">
                            {{ $tna->selesai }}
                        </td>

                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                            <a href="{{ route('admin.quiz_results.show', $tna->id) }}" class="inline-block bg-orange-500 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow hover:bg-orange-600 transition duration-200">
                                Lihat Rekap
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="hover:bg-gray-50">
                        <td colspan="6" class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            Tidak ada data TNA ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection