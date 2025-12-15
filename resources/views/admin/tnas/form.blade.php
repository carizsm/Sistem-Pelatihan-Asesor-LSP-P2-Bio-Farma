@php
    // Logika untuk menentukan mode Tambah atau Edit
    // $tna dan $availableUsers dikirim dari TnaController@edit
    use App\Enums\RealizationStatus;
    $isEdit = isset($tna) && $tna->id;
    $availableUsers = $availableUsers ?? collect();

    // Tentukan apakah Mode Baca Saja (View Only)
    // ReadOnly jika Edit Mode DAN Statusnya COMPLETED atau CANCELED
    $isReadOnly = $isEdit && in_array($tna->realization_status, [
        RealizationStatus::COMPLETED,
        RealizationStatus::CANCELED
    ]);
@endphp

@extends('layouts.admin')

{{-- Judul dinamis --}}
@section('title', $isEdit ? 'Ubah Data TNA' : 'Tambah Data TNA')
@section('page_title', $isEdit ? 'TNA - Ubah Data TNA' : 'TNA - Tambah Data TNA')
@section('content')

<div class="max-w-4xl mx-auto">
    @if($isReadOnly)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Perhatian:</strong> Halaman ini dalam mode <u>View-Only</u> karena status pelatihan sudah Selesai atau Dibatalkan. Anda tidak dapat mengubah data.
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 border border-green-300 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    
    {{-- REVISI: Tambahkan blok ini untuk menampilkan error validasi --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <strong>Terjadi kesalahan validasi:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        {{ $isEdit ? 'Ubah Data TNA' : 'Form TNA Baru' }}
    </h1>
    
    <form action="{{ $isEdit ? route('admin.tnas.update', $tna->id) : route('admin.tnas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- BAGIAN 1: Informasi Umum TNA --}}
        <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Informasi Umum TNA</h2>
            <div class="grid grid-cols-2 gap-6">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelatihan</label>
                    {{-- REVISI: name="nama_pelatihan" -> name="name" --}}
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           value="{{ old('name', $tna->name ?? '') }}" required @disabled($isReadOnly)>
                </div>
                 <div>
                    <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-1">Skor Kelulusan</label>
                    {{-- (Pastikan 'passing_score' ada di $fillable Tna.php) --}}
                    <input type="text" id="passing_score" name="passing_score" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           value="{{ old('passing_score', $tna->passing_score ?? '') }}" placeholder="Contoh: 75" required @disabled($isReadOnly)>
                </div>

                <div>
                    <label for="pembuat" class="block text-sm font-medium text-gray-700 mb-1">Pembuat</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500" 
                           value="{{ $isEdit ? ($tna->user->name ?? Auth::user()->name) : Auth::user()->name }}" readonly>
                </div>
                <div>
                    <label for="unit_pembuat" class="block text-sm font-medium text-gray-700 mb-1">Unit Pembuat</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500" 
                           value="{{ $isEdit ? ($tna->user->unit->unit_name ?? Auth::user()->unit->unit_name) : (Auth::user()->unit->unit_name ?? 'N/A') }}" readonly>
                </div>
                
                <div>
                    <label for="tna_code" class="block text-sm font-medium text-gray-700 mb-1">Kode TNA</label>
                    <input type="text" id="tna_code" name="tna_code" class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500" 
                           value="{{ old('tna_code', $tna->tna_code ?? 'Akan digenerate otomatis') }}" readonly>
                </div>
                <div>
                    <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    {{-- REVISI: name="periode" -> name="period" --}}
                    <input type="text" id="period" name="period" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           value="{{ old('period', $tna->period ?? date('Y')) }}" placeholder="Contoh: 2025" required @disabled($isReadOnly)>
                </div>
                
                <div class="col-span-2">
                    <label for="method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pelatihan</label>
                    {{-- REVISI: name="metode_pelatihan" -> name="method" --}}
                    <input type="text" id="method" name="method" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           value="{{ old('method', $tna->method ?? '') }}" placeholder="Contoh: In-House / Online" required @disabled($isReadOnly)>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: Jadwal Pelatihan --}}
        <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Jadwal Pelatihan</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    {{-- REVISI: name="tanggal_mulai" -> name="start_date" --}}
                    <input type="datetime-local" id="start_date" name="start_date" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           value="{{ old('start_date', ($isEdit && $tna->start_date) ? $tna->start_date->format('Y-m-d\TH:i') : '') }}" required @disabled($isReadOnly)>
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    {{-- REVISI: name="tanggal_selesai" -> name="end_date" --}}
                    <input type="datetime-local" id="end_date" name="end_date" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           value="{{ old('end_date', ($isEdit && $tna->end_date) ? $tna->end_date->format('Y-m-d\TH:i') : '') }}" required @disabled($isReadOnly)>
                </div>
                
                <div>
                    <label for="speaker" class="block text-sm font-medium text-gray-700 mb-1">Pembicara</label>
                    {{-- REVISI: name="pembicara" -> name="speaker" --}}
                    <input type="text" id="speaker" name="speaker" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           value="{{ old('speaker', $tna->speaker ?? '') }}" placeholder="Nama Pembicara/Instruktur" required @disabled($isReadOnly)>
                </div>
                <div>
                    <label for="batch" class="block text-sm font-medium text-gray-700 mb-1">Batch Kegiatan</label>
                    <input type="text" id="batch" name="batch" class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500" 
                           value="{{ old('batch', $tna->batch ?? 'Akan digenerate otomatis') }}" readonly>
                </div>

                <div class="col-span-2">
                     <label for="spt_file_path" class="block text-sm font-medium text-gray-700 mb-1">File SPT Instruktur (Opsional)</label>
                     {{-- REVISI: name="file_spt" -> name="spt_file_path" --}}
                     <input type="file" id="spt_file_path" name="spt_file_path" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" @disabled($isReadOnly)>
                     @if($isEdit && $tna->spt_file_path)
                        <small class="text-gray-500">File saat ini: <a href="{{ asset('storage/'. $tna->spt_file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></small>
                     @endif
                </div>
            </div>
        </div>

        {{-- BAGIAN 3: Analisis dan Justifikasi --}}
        <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">Analisis dan Justifikasi</h2>
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
                    <textarea id="reason" name="reason" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" required @disabled($isReadOnly)>{{ old('reason', $tna->reason ?? '') }}</textarea>
                </div>
                <div>
                    <label for="before_status" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Sebelum</label>
                    <textarea id="before_status" name="before_status" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" required @disabled($isReadOnly)>{{ old('before_status', $tna->before_status ?? '') }}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="goal" class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                    <textarea id="goal" name="goal" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" required @disabled($isReadOnly)>{{ old('goal', $tna->goal ?? '') }}</textarea>
                </div>
                <div>
                    <label for="after_status" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Diharapkan</label>
                    <textarea id="after_status" name="after_status" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500" required @disabled($isReadOnly)>{{ old('after_status', $tna->after_status ?? '') }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="realization_status" class="block text-sm font-medium text-gray-700 mb-1">Status Realisasi</label>
                <select id="realization_status" name="realization_status" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 appearance-none" required>
                    @php
                        $currentValue = old('realization_status', $tna->realization_status ?? RealizationStatus::OPEN);
                        // Kalau dia Objek Enum, ambil ->value. Kalau string, biarkan.
                        if ($currentValue instanceof RealizationStatus) {
                            $currentValue = $currentValue->value;
                        }
                    @endphp
                    @foreach(RealizationStatus::cases() as $status)
                        <option value="{{ $status->value }}" 
                            {{ $currentValue == $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>   
            </div>
        </div>

        {{-- Tombol Aksi Utama untuk TNA Form --}}
        <div class="flex justify-center space-x-4 pb-8">
            <a href="{{ route('admin.tnas.index') }}" class="px-6 py-2 border border-gray-400 text-gray-700 font-semibold rounded-lg shadow-sm bg-gray-300 hover:bg-gray-400 transition duration-200">
                Kembali
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-200">
                {{ $isEdit ? 'Simpan Perubahan TNA' : 'Simpan TNA' }}
            </button>
        </div>
        
    </form> {{-- Form TNA UTAMA SELESAI DI SINI --}}

    {{-- BAGIAN 4: Daftar Karyawan Peserta (Selalu Tampil) --}}
    {{-- Form ini TERPISAH dari form TNA utama untuk menghindari nested forms --}}
    <div id="section-peserta" class="bg-white p-8 rounded-xl shadow-lg mb-8" x-data="{ showParticipantForm: false }">
        
        {{-- Notifikasi Sukses Penambahan Peserta --}}
        @if(session('participant_success'))
            <div class="mb-4 p-4 rounded bg-green-100 border border-green-300 text-green-800 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('participant_success') }}</span>
            </div>
        @endif
        
        {{-- Error Penambahan Peserta --}}
        @if(session('participant_error'))
            <div class="mb-4 p-4 rounded bg-red-100 border border-red-300 text-red-800 flex items-center gap-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('participant_error') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-700 text-center">Daftar Karyawan Peserta ({{ $isEdit ? $tna->registrations->count() : 0 }})</h2>
            @php
                $isStatusOpen = isset($tna) && $tna->realization_status === RealizationStatus::OPEN;
            @endphp            
            <button
                type="button"
                @click="showParticipantForm = !showParticipantForm"
                class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-700 transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed"
                @disabled(!$isEdit || !$isStatusOpen)
                title="{{ !$isEdit ? 'Simpan TNA terlebih dahulu untuk menambah peserta' : (!$isStatusOpen ? 'Status harus Belum Terealisasi untuk edit peserta' : 'Pilih peserta') }}"
            >
                <span x-show="!showParticipantForm">Pilih Peserta</span>
                <span x-show="showParticipantForm">Tutup Form</span>
            </button>
        </div>

        {{-- FORM TAMBAH PESERTA (Hanya muncul jika di-klik DAN mode Edit) --}}
        <div x-show="showParticipantForm" class="border border-gray-200 rounded-lg p-4 mb-6 bg-gray-50 transition" x-cloak>
            <h3 class="font-semibold mb-2">Tambah Peserta Baru</h3>
            <p class="text-sm text-gray-600 mb-4">Hanya *trainee* yang belum terdaftar di TNA ini yang muncul.</p>
            
            <form action="{{ $isEdit ? route('admin.registrations.store', $tna->id) : '#' }}" method="POST">
                @csrf
                <div class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Peserta</label>
                        <select id="user_id" name="user_id" class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 appearance-none" required>
                            <option value="">Pilih Nama Karyawan</option>
                            @forelse($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nik }})</option>
                            @empty
                                <option value="" disabled>Semua trainee sudah terdaftar</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-200">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABEL PESERTA SAAT INI --}}
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Karyawan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            NIK
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Unit
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($isEdit)
                        @forelse($tna->registrations as $registration)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                {{ $registration->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                {{ $registration->user->nik ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                {{ $registration->user->unit->unit_name ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                @if($isStatusOpen)
                                    <form action="{{ route('admin.registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Hapus peserta ini dari TNA?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 mx-1 align-middle">
                                            <img src="{{ asset('icons/Button Trash.svg') }}" class="w-8 h-8 inline" alt="Delete">
                                        </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                Belum ada peserta yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="4" class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                Peserta hanya dapat ditambahkan setelah TNA disimpan (mode Edit).
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection