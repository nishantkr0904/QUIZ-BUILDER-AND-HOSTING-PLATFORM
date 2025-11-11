<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quiz routes
    Route::resource('quizzes', QuizController::class);
    
    // Questions management
    Route::resource('quizzes.questions', QuestionController::class);
    Route::post('quizzes/{quiz}/questions/reorder', [QuestionController::class, 'reorder'])
        ->name('quizzes.questions.reorder');

    // Category management
    Route::resource('categories', CategoryController::class);
    
    // User management
    Route::resource('users', UserController::class);
});
