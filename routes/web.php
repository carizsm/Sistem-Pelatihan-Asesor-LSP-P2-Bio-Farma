<?php

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