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
Route::get('/', [PublicHomeController::class, 'welcome'])->name('welcome');

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

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});

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

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Quiz Management
    Route::get('/quizzes', [QuizController::class, 'adminIndex'])->name('admin.quizzes');
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('admin.quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('admin.quizzes.store');
    Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('admin.quizzes.edit');
    Route::put('/quizzes/{id}', [QuizController::class, 'update'])->name('admin.quizzes.update');
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy'])->name('admin.quizzes.destroy');
    
    // Question Management
    Route::get('/quizzes/{quiz_id}/questions', [QuestionController::class, 'index'])->name('admin.questions');
    Route::get('/quizzes/{quiz_id}/questions/create', [QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/quizzes/{quiz_id}/questions', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');
    Route::post('/questions/reorder', [QuestionController::class, 'reorder'])->name('admin.questions.reorder');
    
    // Category Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{id}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
});

// AJAX endpoints for quiz answer saving and submission
Route::middleware(['auth'])->prefix('ajax')->group(function () {
    Route::post('/quiz/{quizId}/save-answer', [\App\Http\Controllers\QuizAjaxController::class, 'saveAnswer'])->name('ajax.quiz.saveAnswer');
    Route::post('/quiz/{quizId}/submit', [\App\Http\Controllers\QuizAjaxController::class, 'submit'])->name('ajax.quiz.submit');
});

Auth::routes();
