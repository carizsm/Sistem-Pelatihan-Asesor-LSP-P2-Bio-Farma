@extends('layouts.admin')

@section('title', 'Rincian Skor Evaluasi 2')
{{-- REVISI: Menggunakan $tna->name untuk judul --}}
@section('page_title', 'Evaluasi 2 - Rincian Skor (' . ($tna->name ?? 'N/A') . ')')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rincian Skor Evaluasi 2</h1>
        
        {{-- REVISI: Menggunakan $tna->name --}}
        <p class="text-xl font-semibold text-gray-700">{{ $tna->name ?? 'N/A' }}</p>
        <p class="text-sm text-gray-500 mb-8">
            Tanggal Pelaksanaan: 
            {{-- REVISI: Menggunakan $tna->start_date --}}
            {{ $tna->start_date ? \Carbon\Carbon::parse($tna->start_date)->format('d F Y') : 'N/A' }}
            - 
            {{-- REVISI: Menggunakan $tna->end_date --}}
            {{ $tna->end_date ? \Carbon\Carbon::parse($tna->end_date)->format('d F Y') : 'N/A' }}
        </p>

        {{-- Statistik Pre-Test --}}
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4 text-center">Statistik Pre-Test</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $preTestStats['total_participants'] }}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Rata-rata Skor</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($preTestStats['average_score'] ?? 0, 2) }}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Skor Tertinggi</p>
                    <p class="text-2xl font-bold text-green-600">{{ $preTestStats['highest_score'] ?? 'N/A' }}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Skor Terendah</p>
                    <p class="text-2xl font-bold text-red-600">{{ $preTestStats['lowest_score'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Statistik Post-Test --}}
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-4 text-center">Statistik Post-Test (Passing Score: {{ $passingScore }})</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-4 bg-blue-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Lulus</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $postTestStats['passed'] }}</p>
                </div>
                <div class="p-4 bg-red-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Gagal</p>
                    <p class="text-2xl font-bold text-red-800">{{ $postTestStats['failed'] }}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Rata-rata Skor</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($postTestStats['average_score'] ?? 0, 2) }}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Tingkat Lulus</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($postTestStats['pass_rate'] ?? 0, 1) }}%</p>
                </div>
            </div>
        </div>

        {{-- Tabel Rincian Skor --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-4 text-center pt-6">Rincian Skor Peserta</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-orange-500 text-white">
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                NIK
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                Nama Karyawan
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                Pre-Test
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                Post-Test
                            </th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($postTestAttempts as $postAttempt)
                            @php
                                $preAttempt = $preTestAttempts->firstWhere('registration_id', $postAttempt->registration_id);
                                $preScore = $preAttempt ? $preAttempt->score : 'N/A';
                                $postScore = $postAttempt->score;
                                $status = $postScore >= $passingScore ? 'Lulus' : 'Tidak Lulus';
                                $user = $postAttempt->registration->user;
                            @endphp
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="px-5 py-4 bg-white text-sm text-gray-800">{{ $user->nik ?? 'N/A' }}</td>
                                <td class="px-5 py-4 bg-white text-sm text-gray-800">{{ $user->name ?? 'N/A' }}</td>
                                <td class="px-5 py-4 bg-white text-sm text-gray-800">{{ $preScore }}</td>
                                <td class="px-5 py-4 bg-white text-sm text-gray-800">{{ $postScore }}</td>
                                <td class="px-5 py-4 bg-white text-sm text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status == 'Lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                        <tr class="hover:bg-gray-50 border-b">
                            <td colspan="5" class="px-5 py-4 bg-white text-sm text-center text-gray-500">
                                Belum ada peserta yang menyelesaikan kuis.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="flex justify-center mt-8">
            <a href="{{ route('admin.quiz_results.index') }}" class="px-8 py-3 text-lg border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-400 hover:bg-gray-500 transition duration-200">
                Kembali
            </a>
        </div>
    </div>
@endsection