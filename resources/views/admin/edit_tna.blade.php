@extends('layouts.admin')

{{-- Judul untuk tab browser --}}
@section('title', 'Edit Data TNA')

{{-- Judul yang tampil di navbar atas --}}
@section('page_title', 'Evaluasi 1 - Edit Data TNA')

{{-- Konten utama halaman --}}
@section('content')
    
    @php
        // Data Dummy untuk Halaman Edit TNA
        $tna = (object) [
            // Informasi Umum
            'nomor_tna' => 'TNA.202509.001',
            'tanggal_pengajuan' => '21/09/2025',
            'unit' => 'Produksi Vaksin Virus',
            'periode' => '2025',
            'pembuat' => 'Jaenudin',
            'nik_pembuat' => '6821408',
            'jabatan_pembuat' => 'Pelaksana',
            'unit_kerja_pembuat' => 'Produksi Vaksin Virus',
            'pelatihan' => 'Microsoft Excel',
            'jenis_pelatihan' => 'Knowledge',
            'metode_pelatihan' => 'Presentation',
            
            // Jadwal Pelatihan
            'tanggal_mulai' => '2025-09-22', // Format Y-m-d untuk input type date
            'tanggal_selesai' => '2025-09-22',
            'jam_mulai' => '16:57',
            'jam_selesai' => '17:03',
            'pembicara' => 'Christoper Nolan',
            'status_kegiatan' => 'Pertama',
            'file_spt' => 'SOP Imam - IMMSU.pdf',
            
            // Analisis dan Justifikasi
            'alasan' => 'a',
            'kondisi_sebelum' => 'b',
            'tujuan' => 'c',
            'kondisi_diharapkan' => 'd',
            'status_realisasi' => 'Realisasi',
        ];

        // Daftar Karyawan Dummy
        $peserta = [
            ['nama' => 'Diah Novitasari', 'nik' => '6801397', 'unit' => 'Teknologi Informasi'],
            ['nama' => 'Budi Santoso', 'nik' => '11223344', 'unit' => 'Penjaminan Mutu dan Mitra Manufaktur'],
        ];
    @endphp

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Form TNA</h1>
        
        {{-- Form action dinonaktifkan sementara. Gunakan metode PUT/PATCH untuk update --}}
        <form action="#" method="POST" onsubmit="alert('Data TNA tidak diubah (dummy mode).'); return false;" enctype="multipart/form-data">
            @csrf 
            @method('PUT')

            {{-- BAGIAN 1: Informasi Umum TNA --}}
            <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
                <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Informasi Umum TNA</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor TNA (Otomatis)</label>
                        <input type="text" class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" value="{{ $tna->nomor_tna }}" readonly>
                    </div>
                    <div>
                        <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                        <input type="text" id="tanggal_pengajuan" name="tanggal_pengajuan" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-gray-100 text-gray-800" value="{{ $tna->tanggal_pengajuan }}" readonly>
                    </div>
                    
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <select id="unit" name="unit" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800 appearance-none" required>
                            <option value="Produksi Vaksin Virus" {{ $tna->unit == 'Produksi Vaksin Virus' ? 'selected' : '' }}>Produksi Vaksin Virus</option>
                            <option value="Teknologi Informasi" {{ $tna->unit == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                        </select>
                    </div>
                    <div>
                        <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <input type="text" id="periode" name="periode" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->periode }}" required>
                    </div>
                    
                    <div>
                        <label for="pembuat" class="block text-sm font-medium text-gray-700 mb-1">Pembuat</label>
                        <select id="pembuat" name="pembuat" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800 appearance-none" required>
                            <option value="Jaenudin" selected>Jaenudin</option>
                        </select>
                    </div>
                    <div>
                        <label for="nik_pembuat" class="block text-sm font-medium text-gray-700 mb-1">NIK Pembuat</label>
                        <input type="text" id="nik_pembuat" name="nik_pembuat" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->nik_pembuat }}" readonly>
                    </div>

                    <div>
                        <label for="jabatan_pembuat" class="block text-sm font-medium text-gray-700 mb-1">Jabatan Pembuat</label>
                        <input type="text" id="jabatan_pembuat" name="jabatan_pembuat" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->jabatan_pembuat }}" readonly>
                    </div>
                    <div>
                        <label for="unit_kerja_pembuat" class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja Pembuat</label>
                        <input type="text" id="unit_kerja_pembuat" name="unit_kerja_pembuat" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->unit_kerja_pembuat }}" readonly>
                    </div>

                    <div class="col-span-2">
                        <label for="pelatihan" class="block text-sm font-medium text-gray-700 mb-1">Pilih Pelatihan</label>
                        <select id="pelatihan" name="pelatihan" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800 appearance-none" required>
                            <option value="Microsoft Excel" {{ $tna->pelatihan == 'Microsoft Excel' ? 'selected' : '' }}>Microsoft Excel</option>
                            <option value="Komunikasi Efektif" {{ $tna->pelatihan == 'Komunikasi Efektif' ? 'selected' : '' }}>Komunikasi Efektif</option>
                        </select>
                    </div>

                    <div>
                        <label for="jenis_pelatihan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelatihan</label>
                        <input type="text" id="jenis_pelatihan" name="jenis_pelatihan" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->jenis_pelatihan }}" required>
                    </div>
                    <div>
                        <label for="metode_pelatihan" class="block text-sm font-medium text-gray-700 mb-1">Metode Pelatihan</label>
                        <input type="text" id="metode_pelatihan" name="metode_pelatihan" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->metode_pelatihan }}" required>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: Jadwal Pelatihan --}}
            <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
                <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Jadwal Pelatihan</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->tanggal_mulai }}" required>
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->tanggal_selesai }}" required>
                    </div>
                    
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->jam_mulai }}" required>
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->jam_selesai }}" required>
                    </div>

                    <div>
                        <label for="pembicara" class="block text-sm font-medium text-gray-700 mb-1">Pembicara</label>
                        <input type="text" id="pembicara" name="pembicara" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->pembicara }}" required>
                    </div>
                    <div>
                        <label for="status_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">Status Kegiatan</label>
                        <input type="text" id="status_kegiatan" name="status_kegiatan" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" value="{{ $tna->status_kegiatan }}" placeholder="Contoh: Pertama, Kedua, Ketiga, dst." required>
                    </div>

                    <div class="col-span-2">
                         <label class="block text-sm font-medium text-gray-700 mb-1">File SPT Instruktur (File saat ini: **{{ $tna->file_spt }}**)</label>
                         <div class="flex space-x-2">
                            <input type="file" id="file_spt" name="file_spt" class="hidden">
                            <label for="file_spt" class="cursor-pointer flex-grow text-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                                Pilih File...
                            </label>
                            <button type="button" class="px-4 py-2 bg-lsp-primary text-white font-semibold rounded-lg shadow hover:bg-orange-600 transition duration-200">
                                Lihat File SPT
                            </button>
                         </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 3: Analisis dan Justifikasi --}}
            <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
                <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Analisis dan Justifikasi</h2>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="alasan" class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
                        <textarea id="alasan" name="alasan" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" required>{{ $tna->alasan }}</textarea>
                    </div>
                    <div>
                        <label for="kondisi_sebelum" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Sebelum</label>
                        <textarea id="kondisi_sebelum" name="kondisi_sebelum" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" required>{{ $tna->kondisi_sebelum }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                        <textarea id="tujuan" name="tujuan" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" required>{{ $tna->tujuan }}</textarea>
                    </div>
                    <div>
                        <label for="kondisi_diharapkan" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Diharapkan</label>
                        <textarea id="kondisi_diharapkan" name="kondisi_diharapkan" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800" required>{{ $tna->kondisi_diharapkan }}</textarea>
                    </div>
                </div>
                <div>
                    <label for="status_realisasi" class="block text-sm font-medium text-gray-700 mb-1">Status Realisasi</label>
                    <select id="status_realisasi" name="status_realisasi" class="w-full px-4 py-2 border rounded-lg focus:ring-lsp-primary focus:border-lsp-primary bg-white text-gray-800 appearance-none" required>
                        <option value="Realisasi" {{ $tna->status_realisasi == 'Realisasi' ? 'selected' : '' }}>Realisasi</option>
                        <option value="Tertunda" {{ $tna->status_realisasi == 'Tertunda' ? 'selected' : '' }}>Tertunda</option>
                        <option value="Batal" {{ $tna->status_realisasi == 'Batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
            </div>

            {{-- BAGIAN 4: Daftar Karyawan --}}
            <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
                <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Daftar Karyawan Peserta</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider rounded-tl-lg">
                                    Nama Karyawan
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    NIK
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-lsp-primary text-left text-xs font-semibold text-white uppercase tracking-wider rounded-tr-lg">
                                    Nama Unit
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Looping Data Karyawan --}}
                            @foreach ($peserta as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $p['nama'] }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $p['nik'] }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $p['unit'] }}</td>
                                </tr>
                            @endforeach
                            {{-- Baris kosong untuk visualisasi tabel panjang --}}
                            <tr><td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">&nbsp;</td><td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"></td><td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"></td></tr>
                            <tr><td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">&nbsp;</td><td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"></td><td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-center space-x-4 pb-8">
                {{-- Tombol Kembali mengarah ke halaman daftar TNA --}}
                <a href="{{ url('admin/tna') }}" class="px-6 py-2 border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-400 hover:bg-gray-500 transition duration-200">
                    Kembali
                </a>
                <button type="submit" class="px-6 py-2 bg-lsp-green text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection