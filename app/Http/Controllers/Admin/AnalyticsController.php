<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Overall Statistics
        $totalQuizzes = Quiz::count();
        $totalAttempts = Result::where('completed', true)->count();
        $totalUsers = User::count();
        $averageScore = Result::where('completed', true)->avg('score');

        // Quiz Performance
        $quizPerformance = Quiz::withCount(['results' => function($query) {
            $query->where('completed', true);
        }])
        ->with(['results' => function($query) {
            $query->where('completed', true);
        }])
        ->get()
        ->map(function($quiz) {
            return [
                'title' => $quiz->title,
                'attempts' => $quiz->results_count,
                'average_score' => $quiz->results->avg('score'),
                'pass_rate' => $quiz->results->where('score', '>=', $quiz->passing_score)->count() / ($quiz->results_count ?: 1) * 100,
                'average_time' => $quiz->results->avg('time_taken')
            ];
        });

        // User Engagement Over Time
        $userEngagement = Result::where('completed', true)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as attempts'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Category Performance
        $categoryPerformance = Category::withCount(['quizzes', 'quizResults'])
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'quiz_count' => $category->quizzes_count,
                    'total_attempts' => $category->quiz_results_count,
                    'average_score' => $category->quizResults->avg('score')
                ];
            });

        // Recent Activity
        $recentActivity = Result::with(['user', 'quiz'])
            ->where('completed', true)
            ->latest()
            ->take(10)
            ->get();

        // Difficulty Level Analysis
        $difficultyAnalysis = Quiz::select('difficulty')
            ->selectRaw('COUNT(*) as quiz_count')
            ->selectRaw('(SELECT AVG(score) FROM results WHERE results.quiz_id = quizzes.id AND completed = 1) as average_score')
            ->groupBy('difficulty')
            ->get();

        return view('admin.analytics.index', compact(
            'totalQuizzes',
            'totalAttempts',
            'totalUsers',
            'averageScore',
            'quizPerformance',
            'userEngagement',
            'categoryPerformance',
            'recentActivity',
            'difficultyAnalysis'
        ));
    }
    
    public function quizDetails($id)
    {
        $quiz = Quiz::with(['results' => function($query) {
            $query->where('completed', true)->with('user');
        }])->findOrFail($id);

        $timeDistribution = $quiz->results
            ->groupBy(function($result) {
                return floor($result->time_taken / 300) * 5; // Group by 5-minute intervals
            })
            ->map->count();

        $scoreDistribution = $quiz->results
            ->groupBy(function($result) {
                return floor($result->score / 10) * 10; // Group by 10-point intervals
            })
            ->map->count();

        $questionAnalysis = $quiz->questions->map(function($question) use ($quiz) {
            $attempts = $quiz->results->pluck('details')->flatten(1);
            $questionAttempts = $attempts->where('question_id', $question->id);
            
            return [
                'question' => $question->question_text,
                'correct_count' => $questionAttempts->where('is_correct', true)->count(),
                'total_attempts' => $questionAttempts->count(),
                'success_rate' => $questionAttempts->count() > 0 
                    ? ($questionAttempts->where('is_correct', true)->count() / $questionAttempts->count() * 100)
                    : 0
            ];
        });

        return view('admin.analytics.quiz-details', compact(
            'quiz',
            'timeDistribution',
            'scoreDistribution',
            'questionAnalysis'
        ));
    }
}
