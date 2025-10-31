<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Grup untuk semua rute admin
Route::prefix('admin')->group(function () {
    
    // Halaman Daftar Asesor
    Route::get('/asesor', function () {
        return view('admin.asesor'); 
    })->name('admin.asesor');
    // Halaman Tambah Asesor
    Route::get('/asesor/tambah', function () {
        return view('admin.tambah_asesor'); 
    })->name('admin.asesor.create');
    // Halaman Ubah Asesor (Menerima parameter NIK)
    Route::get('/asesor/ubah/{nik}', function ($nik) {
        return view('admin.ubah_asesor'); 
    })->name('admin.asesor.edit');

    // Halaman Daftar TNA
    Route::get('/tna', function () {
        return view('admin.tna'); 
    })->name('admin.tna');
    // Halaman Tambah TNA
    Route::get('/tna/tambah', function () {
        return view('admin.tambah_tna'); 
    })->name('admin.tna.create');
    // Halaman Ubah TNA (Menerima parameter ID)
    Route::get('/tna/ubah/{id}', function ($id) {
        return view('admin.edit_tna'); 
    })->name('admin.tna.edit');

    // Halaman Daftar Analisis Evaluasi 1
    Route::get('/evaluasi-1', function () {
        return view('admin.evaluasi1'); 
    })->name('admin.evaluasi1');
    // Halaman Rekap Evaluasi 1 (Menerima parameter nama pelatihan)
    Route::get('/evaluasi-1/rekap/{pelatihan}', function ($pelatihan) {
        return view('admin.rekap_evaluasi1'); 
    })->name('admin.evaluasi1.rekap');

    // Halaman Daftar Analisis Evaluasi 2
    Route::get('/evaluasi-2', function () {
        return view('admin.evaluasi2'); 
    })->name('admin.evaluasi2');
    // Halaman Rincian Skor Evaluasi 2 (Menerima parameter nama pelatihan)
    Route::get('/evaluasi-2/rincian/{pelatihan}', function ($pelatihan) {
        return view('admin.rincian_evaluasi2'); 
    })->name('admin.evaluasi2.rincian');

    // Halaman Daftar Soal Pelatihan
    Route::get('/atur-soal-quiz', function () {
        return view('admin.atursoalquiz'); 
    })->name('admin.atursoalquiz');
    // Halaman Kelola Soal Quiz (Menerima parameter nama pelatihan)
    Route::get('/atur-soal-quiz/kelola/{pelatihan}', function ($pelatihan) {
        return view('admin.kelola_atursoalquiz'); 
    })->name('admin.atursoalquiz.kelola');

});

//rute peserta
Route::prefix('peserta')->group(function () {
    Route::get('/presensi', function () {
        return view('peserta.presensi');
    })->name('peserta.presensi');

    Route::get('/evaluasi1', function () {
        return view('peserta.evaluasi1');
    })->name('peserta.evaluasi1');

    Route::get('/evaluasi2', function () {
        return view('peserta.evaluasi2');
    })->name('peserta.evaluasi2');

    Route::get('/feedback',function () {
        return view('peserta.feedback');
    })->name('peserta.feedback');

    Route::get('/quiz',function () {
        return view('peserta.quiz');
    })->name('peserta.quiz');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';