@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Rincian Skor Evaluasi 2')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Evaluasi 2 - Lihat Rincian Skor (Microsoft Excel)')

{{-- Konten utama halaman --}}
@section('content')
    
    @php
        // Data Dummy untuk Halaman Rincian
        $pelatihan = [
            'nama' => 'Microsoft Excel',
            'tanggal_mulai' => '22 September 2025',
            'tanggal_selesai' => '22 September 2025',
        ];

        $peserta = [
            ['nik' => '6801397', 'nama' => 'Diah Novitasari', 'pre_test' => 20, 'post_test' => 100, 'status' => 'Lulus'],
            ['nik' => '11223344', 'nama' => 'Budi Santoso', 'pre_test' => 60, 'post_test' => 90, 'status' => 'Lulus'],
            // Tambahkan data dummy lain di sini
        ];
    @endphp

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rincian Skor Evaluasi 2</h1>
        <p class="text-xl font-semibold text-gray-700">{{ $pelatihan['nama'] }}</p>
        <p class="text-sm text-gray-500 mb-8">Tanggal Pelaksanaan: {{ $pelatihan['tanggal_mulai'] }} - {{ $pelatihan['tanggal_selesai'] }}</p>

        {{-- Tabel Rincian Skor --}}
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider rounded-tl-lg">
                                NIK
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Nama Karyawan
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Pre-Test
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Post-Test
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-center text-xs font-semibold text-white uppercase tracking-wider rounded-tr-lg">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peserta as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">{{ $p['nik'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">{{ $p['nama'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">{{ $p['pre_test'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-800">{{ $p['post_test'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                                    {{-- Menggunakan dropdown sederhana (select) untuk Aksi/Status --}}
                                    <select class="p-1 border rounded-lg shadow-sm text-sm">
                                        <option value="Lulus" {{ $p['status'] == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                        <option value="Tidak Lulus" {{ $p['status'] == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="flex justify-center mt-8">
            {{-- Link kembali ke halaman daftar evaluasi 2 --}}
            <a href="{{ url('admin/evaluasi-2') }}" class="px-8 py-3 text-lg border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-400 hover:bg-gray-500 transition duration-200">
                Kembali
            </a>
        </div>
    </div>
@endsection