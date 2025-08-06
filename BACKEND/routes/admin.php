<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quiz routes
    Route::resource('quizzes', QuizController::class);
    
    // Questions management
    Route::resource('quizzes.questions', QuestionController::class);
    Route::post('quizzes/{quiz}/questions/reorder', [QuestionController::class, 'reorder'])
        ->name('quizzes.questions.reorder');
});
