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

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // User Management
    Route::resource('users', UserController::class)->except(['show']);
    
    // TNA Management
    Route::resource('tnas', TnaController::class);
    Route::post('tnas/{tna}/registrations', [TnaController::class, 'storeRegistration'])->name('registrations.store');
    Route::delete('registrations/{registration}', [TnaController::class, 'destroyRegistration'])->name('registrations.destroy');
    
    // Evaluation Results - Feedback
    Route::get('feedback-results', [EvaluationResultController::class, 'indexFeedback'])->name('feedback_results.index');
    Route::get('feedback-results/{tna}/show', [EvaluationResultController::class, 'showFeedbackReport'])->name('feedback_results.show');
    
    // Evaluation Results - Quiz
    Route::get('quiz-results', [EvaluationResultController::class, 'indexQuiz'])->name('quiz_results.index');
    Route::get('quiz-results/{tna}/show', [EvaluationResultController::class, 'showQuizReport'])->name('quiz_results.show');
    
    // Quiz Questions Management
    Route::get('quiz-questions', [QuizQuestionController::class, 'index'])->name('quiz_questions.index');
    Route::get('quiz-questions/download-template', [QuizQuestionController::class, 'downloadTemplate'])->name('quiz_questions.downloadTemplate');
    Route::get('quiz-questions/{tna}/kelola', [QuizQuestionController::class, 'show'])->name('quiz_questions.show');
    Route::post('quiz-questions/{tna}/import', [QuizQuestionController::class, 'importExcel'])->name('quiz_questions.import');
    Route::delete('quiz-questions/{question}', [QuizQuestionController::class, 'destroy'])->name('quiz_questions.destroy');
    Route::delete('quiz-questions/{tna}/destroy-all', [QuizQuestionController::class, 'destroyAll'])->name('quiz_questions.destroyAll');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('peserta')->name('peserta.')->group(function () {
        // Presence
        Route::get('presensi', [PresenceController::class, 'show'])->name('presensi');
        
        // Feedback Evaluation
        Route::get('evaluasi1', [EvaluationController::class, 'indexFeedback'])->name('evaluasi1');
        
        // Quiz Evaluation
        Route::get('evaluasi2', [EvaluationController::class, 'indexQuiz'])->name('evaluasi2');
        Route::get('quiz', fn() => redirect()->route('peserta.evaluasi2'))->name('quiz');
    });
    
    // Presence Actions
    Route::post('presence', [PresenceController::class, 'store'])->name('presence.store');
    Route::put('presence/{presence}', [PresenceController::class, 'update'])->name('presence.update');
    
    // Feedback Evaluation Actions
    Route::get('evaluasi1/{registration}', [EvaluationController::class, 'showFeedbackForm'])->name('evaluasi1.form');
    Route::post('evaluasi1/{registration}', [EvaluationController::class, 'storeFeedback'])->name('evaluasi1.store');
    Route::get('evaluasi1/{registration}/review', [EvaluationController::class, 'reviewFeedback'])->name('evaluasi1.review');
    
    // Quiz Evaluation Actions
    Route::get('evaluasi2/{registration}/{type}', [EvaluationController::class, 'showQuizForm'])->name('evaluasi2.quiz.form');
    Route::post('evaluasi2/{registration}/{type}', [EvaluationController::class, 'storeQuiz'])->name('evaluasi2.quiz.store');
    Route::get('evaluasi2/{registration}/{type}/review', [EvaluationController::class, 'reviewQuiz'])->name('evaluasi2.review');
    
    // Profile
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

require __DIR__.'/auth.php';