<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TnaController;
use App\Http\Controllers\Admin\EvaluationResultController;
use App\Http\Controllers\Admin\QuizQuestionController;

Route::get('/', function () {
    return view('welcome');
});

// Grup untuk semua rute admin
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    // 1. Asesor (Diubah ke Resource: users)
    // REVISI: Menggunakan Controller dan menambahkan rute POST/PUT/DELETE
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store'); // <-- INI YANG HILANG
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update'); // <-- INI JUGA HILANG
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy'); // <-- (Untuk tombol Hapus)

    // 2. TNA (Diubah ke Resource: tnas)
    // REVISI: Mengganti semua closure dengan TnaController
    Route::get('/tnas', [TnaController::class, 'index'])->name('admin.tnas.index');
    Route::get('/tnas/create', [TnaController::class, 'create'])->name('admin.tnas.create');
    Route::post('/tnas', [TnaController::class, 'store'])->name('admin.tnas.store');
    Route::get('/tnas/{tna}', [TnaController::class, 'show'])->name('admin.tnas.show');
    Route::get('/tnas/{tna}/edit', [TnaController::class, 'edit'])->name('admin.tnas.edit');
    Route::put('/tnas/{tna}', [TnaController::class, 'update'])->name('admin.tnas.update');
    Route::delete('/tnas/{tna}', [TnaController::class, 'destroy'])->name('admin.tnas.destroy');
    
    // REVISI: Rute untuk menambah/menghapus peserta (sesuai TnaController)
    Route::post('/tnas/{tna}/registrations', [TnaController::class, 'storeRegistration'])->name('admin.registrations.store');
    Route::delete('/registrations/{registration}', [TnaController::class, 'destroyRegistration'])->name('admin.registrations.destroy');


    // 3. Evaluasi 1 (Diubah ke Resource: feedback-results)
    // REVISI: Ubah route ini agar menggunakan 'indexFeedback' dari Controller
    Route::get('/feedback-results', [EvaluationResultController::class, 'indexFeedback'])
         ->name('admin.feedback_results.index');

    // REVISI: Ubah route ini agar menggunakan 'showFeedbackReport' dari Controller
    // Ganti parameter {pelatihan} menjadi {tna} agar cocok dengan method (Tna $tna)
    Route::get('/feedback-results/{tna}/show', [EvaluationResultController::class, 'showFeedbackReport'])
         ->name('admin.feedback_results.show');


    // 4. Evaluasi 2 (Diubah ke Resource: quiz-results)
    // REVISI: Ubah route ini agar menggunakan 'indexQuiz' dari Controller
    Route::get('/quiz-results', [EvaluationResultController::class, 'indexQuiz'])
         ->name('admin.quiz_results.index');

    // REVISI: Ubah route ini agar menggunakan 'showQuizReport' dari Controller
    // Ganti parameter {pelatihan} menjadi {tna} agar cocok dengan method (Tna $tna)
    Route::get('/quiz-results/{tna}/show', [EvaluationResultController::class, 'showQuizReport'])
         ->name('admin.quiz_results.show');


    // 5. Atur Soal Quiz (Diubah ke Resource: quiz-questions)
    
    // REVISI: Menggunakan method 'index' dari controller
    Route::get('/quiz-questions', [QuizQuestionController::class, 'index'])
         ->name('admin.quiz_questions.index');

    // REVISI: Menggunakan method 'show' dan parameter {tna} (untuk Route Model Binding)
    Route::get('/quiz-questions/{tna}/kelola', [QuizQuestionController::class, 'show'])
         ->name('admin.quiz_questions.show'); // Mengubah nama route agar konsisten

    // REVISI: Rute baru untuk menangani proses Upload Excel
    Route::post('/quiz-questions/{tna}/import', [QuizQuestionController::class, 'importExcel'])
         ->name('admin.quiz_questions.import');

    // REVISI: Rute baru untuk Download Template
    Route::get('/quiz-questions/download-template', [QuizQuestionController::class, 'downloadTemplate'])
         ->name('admin.quiz_questions.downloadTemplate');

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

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Presensi
    Route::get('/peserta/presensi', [PresenceController::class, 'show'])->name('peserta.presensi');
    Route::post('/presence', [PresenceController::class, 'store'])->name('presence.store');
    Route::put('/presence/{presence}', [PresenceController::class, 'update'])->name('presence.update');
    
    // Evaluasi 1 (Feedback) Routes
    Route::get('/peserta/evaluasi1', [EvaluationController::class, 'indexFeedback'])->name('peserta.evaluasi1');
    Route::get('/evaluasi1/{registration}', [EvaluationController::class, 'showFeedbackForm'])->name('evaluasi1.form');
    Route::post('/evaluasi1/{registration}', [EvaluationController::class, 'storeFeedback'])->name('evaluasi1.store');
    Route::get('/evaluasi1/{registration}/review', [EvaluationController::class, 'reviewFeedback'])->name('evaluasi1.review');
    
    // Evaluasi 2 (Quiz) Routes
    Route::get('/peserta/evaluasi2', [EvaluationController::class, 'indexQuiz'])->name('peserta.evaluasi2');
    Route::get('/evaluasi2/{registration}/{type}', [EvaluationController::class, 'showQuizForm'])->name('evaluasi2.quiz.form');
    Route::post('/evaluasi2/{registration}/{type}', [EvaluationController::class, 'storeQuiz'])->name('evaluasi2.quiz.store');
    Route::get('/evaluasi2/{registration}/{type}/review', [EvaluationController::class, 'reviewQuiz'])->name('evaluasi2.review');
    
    // Backward compatibility route (if needed)
    Route::get('/peserta/quiz', function() {
        return redirect()->route('peserta.evaluasi2');
    })->name('peserta.quiz');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';