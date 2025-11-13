@extends('layouts.admin')

@section('title', 'Rekap Evaluasi 1')
{{-- REVISI: Menggunakan $tna->name untuk judul --}}
@section('page_title', 'Evaluasi 1 - Rekap (' . ($tna->name ?? 'N/A') . ')')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rekap Evaluasi 1 (Feedback)</h1>
        
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

        {{-- Statistik Rata-Rata Skor --}}
        <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Rata-Rata Skor per Kategori</h2>
            
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="text-center p-4 bg-lsp-bg rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Tujuan Pelatihan</p>
                    <p class="text-3xl font-bold text-lsp-primary">{{ number_format($categoryAverages['tujuan'] ?? 0, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-lsp-bg rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Materi Pelatihan</p>
                    <p class="text-3xl font-bold text-lsp-primary">{{ number_format($categoryAverages['materi'] ?? 0, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-lsp-bg rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Alokasi Waktu</p>
                    <p class="text-3xl font-bold text-lsp-primary">{{ number_format($categoryAverages['alokasi_waktu'] ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <div class="text-center p-4 bg-lsp-bg rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Instruktur</p>
                    <p class="text-3xl font-bold text-lsp-primary">{{ number_format($categoryAverages['instruktur'] ?? 0, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-lsp-bg rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Fasilitas Pelatihan</p>
                    <p class="text-3xl font-bold text-lsp-primary">{{ number_format($categoryAverages['fasilitas'] ?? 0, 2) }}</p>
                </div>
                 <div class="text-center p-4 bg-lsp-bg rounded-lg">
                    <p class="text-sm font-medium text-gray-500 mb-1">Hasil Pelatihan</p>
                    <p class="text-3xl font-bold text-lsp-primary">{{ number_format($categoryAverages['hasil_pelatihan'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        
        {{-- Tombol Kembali --}}
        <div class="flex justify-center mt-8">
            <a href="{{ route('admin.feedback_results.index') }}" class="px-8 py-3 text-lg border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-400 hover:bg-gray-500 transition duration-200">
                Kembali
            </a>
        </div>
    </div>
@endsection