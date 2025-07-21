<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\CategoryController;

// Public routes
Route::get('/', [QuizController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Quiz listing and taking
Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.list');
Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('quiz.take');
Route::get('/quiz/{id}/result', [QuizController::class, 'result'])->name('quizzes.result');

// User dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
    Route::get('/my-quizzes', [ResultController::class, 'index'])->name('user.quizzes');
    // ...other user routes
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('/quizzes', [QuizController::class, 'index'])->name('admin.quizzes');
    Route::get('/quizzes/{quiz_id}/questions', [QuestionController::class, 'index'])->name('admin.questions');
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    // ...other admin routes
});

// AJAX endpoints for quiz answer saving and submission
Route::middleware(['auth'])->prefix('ajax')->group(function () {
    Route::post('/quiz/{quizId}/save-answer', [\App\Http\Controllers\QuizAjaxController::class, 'saveAnswer'])->name('ajax.quiz.saveAnswer');
    Route::post('/quiz/{quizId}/submit', [\App\Http\Controllers\QuizAjaxController::class, 'submit'])->name('ajax.quiz.submit');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
