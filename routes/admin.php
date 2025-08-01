<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AnalyticsController;

Route::middleware(['auth', 'admin'])->group(function () {
    // Analytics Routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('/analytics/quiz/{id}', [AnalyticsController::class, 'quizDetails'])->name('admin.analytics.quiz-details');
});
