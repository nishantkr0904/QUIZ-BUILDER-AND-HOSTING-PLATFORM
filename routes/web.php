<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PublicHomeController;

// Public routes
Route::get('/', [PublicHomeController::class, 'welcome'])->name('welcome')->middleware('guest');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // User dashboard and quiz listing
    Route::get('/home', [PublicHomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Quiz routes
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/{id}', [QuizController::class, 'show'])->name('show');
        Route::post('/{quiz}/start', [QuizController::class, 'start'])->name('start');
        Route::get('/{id}/take', [QuizController::class, 'take'])->name('take');
        Route::post('/{id}/submit', [QuizController::class, 'submit'])->name('submit');
        Route::get('/{id}/result', [QuizController::class, 'result'])->name('result');
        Route::get('/{id}/resume', [QuizController::class, 'resume'])->name('resume');
    });
});

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Admin Login Routes
    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/my-quizzes', [ResultController::class, 'index'])->name('user.quizzes');
    Route::get('/results', [ResultController::class, 'results'])->name('user.results');
    Route::get('/quiz-result/{result}', [ResultController::class, 'show'])->name('quizzes.result');
    // ...other user routes
});

// AJAX endpoints for quiz answer saving and submission
Route::middleware(['auth'])->prefix('ajax')->group(function () {
    Route::post('/quiz/{quizId}/save-answer', [\App\Http\Controllers\QuizAjaxController::class, 'saveAnswer'])->name('ajax.quiz.saveAnswer');
    Route::post('/quiz/{quizId}/submit', [\App\Http\Controllers\QuizAjaxController::class, 'submit'])->name('ajax.quiz.submit');
});

Auth::routes();
