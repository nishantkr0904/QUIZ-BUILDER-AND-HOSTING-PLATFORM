<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\User;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        try {
            // Basic stats that don't require relationships
            $stats = [
                'total_quizzes' => 0,
                'total_categories' => 0,
                'total_users' => 0,
                'quiz_attempts' => 0,
                'recent_quizzes' => [],
                'quiz_by_category' => [],
                'recent_users' => []
            ];

            // Try to get each statistic individually
            try { $stats['total_quizzes'] = Quiz::count(); } catch (\Exception $e) { \Log::error($e->getMessage()); }
            try { $stats['total_categories'] = Category::count(); } catch (\Exception $e) { \Log::error($e->getMessage()); }
            try { $stats['total_users'] = User::where('is_admin', 0)->count(); } catch (\Exception $e) { \Log::error($e->getMessage()); }
            try { $stats['quiz_attempts'] = QuizResult::count(); } catch (\Exception $e) { \Log::error($e->getMessage()); }
            
            try {
                $stats['recent_quizzes'] = Quiz::with('category')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } catch (\Exception $e) { \Log::error($e->getMessage()); }
            
            try {
                $stats['quiz_by_category'] = Category::withCount('quizzes')->get();
            } catch (\Exception $e) { \Log::error($e->getMessage()); }
            
            try {
                $stats['recent_users'] = User::where('is_admin', 0)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } catch (\Exception $e) { \Log::error($e->getMessage()); }

            \Log::info('Dashboard Stats:', $stats);
            return view('admin.dashboard', ['stats' => $stats]);
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: ' . $e->getMessage());
            return view('admin.dashboard', ['stats' => [
                'total_quizzes' => 0,
                'total_categories' => 0,
                'total_users' => 0,
                'quiz_attempts' => 0,
                'recent_quizzes' => [],
                'quiz_by_category' => [],
                'recent_users' => []
            ]]);
        }
    }
}
