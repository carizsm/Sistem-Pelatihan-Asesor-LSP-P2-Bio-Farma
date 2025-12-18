@php
    $isEdit = isset($user) && $user->id; 
@endphp

@extends('layouts.admin')

@section('title', $isEdit ? 'Ubah Data User' : 'Tambah Data User')
@section('page_title', $isEdit ? 'Asesor - Ubah Data User' : 'Asesor - Tambah Data User')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            
            <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
                {{ $isEdit ? 'Ubah Data User (Asesor)' : 'Tambah Data User (Asesor)' }}
            </h2>

            <form action="{{ $isEdit ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
                @csrf 
                @if($isEdit)
                    @method('PUT')
                @endif

                {{-- REVISI: Label "Nama Asesor", tapi name="name" (sesuai Controller) --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Asesor</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $user->name ?? '') }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- REVISI: Label "NIK Asesor", tapi name="nik" (sesuai Controller) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">NIK</label>
                    <input type="text" 
                        name="nik" 
                        value="{{ old('nik', $user->nik ?? '') }}"
                        maxlength="10" 
                        minlength="10"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nik') border-red-500 @enderror"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                        placeholder="Masukkan 10 digit angka"
                        required>
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- REVISI: Field Email (WAJIB ADA, sesuai Controller) --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $user->email ?? '') }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500"
                           required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- REVISI: Dropdown Jabatan (name="position_id") (sesuai Controller) --}}
                <div class="mb-4">
                    <label for="position_id" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <select name="position_id" id="position_id" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 appearance-none">
                        <option value="">Pilih Jabatan</option>
                        {{-- Data $positions dikirim dari Controller --}}
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ old('position_id', $isEdit ? $user->position_id : '') == $position->id ? 'selected' : '' }}>
                                {{ $position->position_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- REVISI: Dropdown Unit Kerja (name="unit_id") (sesuai Controller) --}}
                <div class="mb-4">
                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                    <select name="unit_id" id="unit_id" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 appearance-none">
                        <option value="">Pilih Unit Kerja</option>
                        {{-- Data $units dikirim dari Controller --}}
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id', $isEdit ? $user->unit_id : '') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->unit_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- REVISI: Mengganti Checkbox "Pembuat Asesor" dengan Dropdown "Role" (WAJIB ADA, sesuai Controller) --}}
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 appearance-none" required>
                        <option value="trainee" {{ old('role', $isEdit ? $user->role : '') == 'trainee' ? 'selected' : '' }}>Trainee (Asesor)</option>
                        <option value="admin" {{ old('role', $isEdit ? $user->role : '') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- REVISI: Field Password (WAJIB ADA, sesuai Controller) --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500"
                           {{ !$isEdit ? 'required' : '' }}>
                    @if($isEdit)
                        <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password.</small>
                    @endif
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500"
                           {{ !$isEdit ? 'required' : '' }}>
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-500 text-white font-semibold rounded-lg shadow-sm bg-gray-500 hover:bg-gray-600 transition duration-200">
                        Kembali
                    </a>
                    
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-200">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection