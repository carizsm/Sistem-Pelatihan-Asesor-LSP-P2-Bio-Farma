@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Rekap Evaluasi 1')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Evaluasi 1 - Lihat Rekap (Microsoft Excel)')

{{-- Konten utama halaman --}}
@section('content')
    
    @php
        // Data Dummy untuk Halaman Rekap
        $pelatihan = [
            'nama' => 'Microsoft Excel',
            'tanggal_mulai' => '22 September 2025',
            'tanggal_selesai' => '22 September 2025',
            'rata_rata_skor' => [
                'Tujuan Pelatihan' => '3,25',
                'Materi Pelatihan' => '2,83',
                'Alokasi Waktu' => '2,25',
                'Instruktur' => '2,88',
                'Fasilitas Pelatihan' => '3,00',
                'Fasilitas Lain' => '2,75', // Anggap ini yang ketiga di baris bawah
            ]
        ];
    @endphp

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rekap Evaluasi 1 (Feedback)</h1>
        <p class="text-xl font-semibold text-gray-700">{{ $pelatihan['nama'] }}</p>
        <p class="text-sm text-gray-500 mb-8">Tanggal Pelaksanaan: {{ $pelatihan['tanggal_mulai'] }} - {{ $pelatihan['tanggal_selesai'] }}</p>

        {{-- BAGIAN 1: Rata-Rata Skor Per Kategori --}}
        <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Rata-Rata Skor per Kategori</h2>
            
            {{-- Baris 1 Skor --}}
            <div class="grid grid-cols-3 gap-6 mb-6">
                @foreach (['Tujuan Pelatihan', 'Materi Pelatihan', 'Alokasi Waktu'] as $kategori)
                    <div class="text-center p-4 bg-lsp-bg rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">{{ $kategori }}</p>
                        <p class="text-3xl font-bold text-lsp-primary">{{ $pelatihan['rata_rata_skor'][$kategori] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Baris 2 Skor --}}
            <div class="grid grid-cols-3 gap-6">
                 @foreach (['Instruktur', 'Fasilitas Pelatihan', 'Fasilitas Lain'] as $kategori)
                    <div class="text-center p-4 bg-lsp-bg rounded-lg">
                        {{-- Mengubah tampilan Fasilitas Lain menjadi Fasilitas Pelatihan sesuai gambar --}}
                        <p class="text-sm font-medium text-gray-500 mb-1">{{ $kategori === 'Fasilitas Lain' ? 'Fasilitas Pelatihan' : $kategori }}</p> 
                        <p class="text-3xl font-bold text-lsp-primary">{{ $pelatihan['rata_rata_skor'][$kategori] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        
        {{-- BAGIAN 2: Duplikasi Skor per Kategori (Sesuai Gambar) --}}
        {{-- Jika ini adalah pengulangan untuk kategori berbeda (misal: Pre-Post Test) ganti judul dan data --}}
        <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Rata-Rata Skor per Kategori</h2>
            
             {{-- Baris 1 Skor (Duplikasi) --}}
            <div class="grid grid-cols-3 gap-6">
                @foreach (['Tujuan Pelatihan', 'Materi Pelatihan', 'Alokasi Waktu'] as $kategori)
                    <div class="text-center p-4 bg-lsp-bg rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">{{ $kategori }}</p>
                        <p class="text-3xl font-bold text-lsp-primary">{{ $pelatihan['rata_rata_skor'][$kategori] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="flex justify-center mt-8">
            {{-- Link kembali ke halaman daftar evaluasi 1 --}}
            <a href="{{ url('admin/evaluasi-1') }}" class="px-8 py-3 text-lg border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-400 hover:bg-gray-500 transition duration-200">
                Kembali
            </a>
        </div>

    </div>
@endsection