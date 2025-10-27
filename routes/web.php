<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Grup untuk semua rute admin
Route::prefix('admin')->group(function () {
    
    Route::get('/asesor', function () {
        return view('admin.asesor');
    })->name('admin.asesor');

    Route::get('/tna', function () {
        return view('admin.tna');
    })->name('admin.tna');

    Route::get('/evaluasi-1', function () {
        return view('admin.evaluasi1');
    })->name('admin.evaluasi1');

    Route::get('/evaluasi-2', function () {
        return view('admin.evaluasi2');
    })->name('admin.evaluasi2');

    Route::get('/atur-soal-quiz', function () {
        return view('admin.atursoalquiz');
    })->name('admin.atursoalquiz');

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