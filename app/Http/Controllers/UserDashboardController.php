<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's quiz history and scores
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's quiz results with scores and dates
        $results = Result::where('user_id', $user->id)
            ->with(['quiz' => function($query) {
                $query->withCount('questions');
            }, 'quiz.questions'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($result) {
                return $result->quiz !== null; // Filter out results where quiz might have been deleted
            });

        // Calculate comprehensive statistics
        $completedResults = $results->where('completed', true);
        
        $totalTime = $results->sum('time_taken') ?? 0;
        $hours = floor($totalTime / 3600);
        $minutes = floor(($totalTime % 3600) / 60);
        
        $stats = [
            'quizzes_taken' => $results->count(),
            'average_score' => number_format($completedResults->avg('score') ?? 0, 1) . '%',
            'best_score' => number_format($completedResults->max('score') ?? 0, 1) . '%',
            'total_time' => $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m",
            'completed_quizzes' => $completedResults->count(),
            'total_questions' => $results->sum(function($result) {
                return $result->quiz->questions->count();
            }),
            'passing_rate' => $completedResults->count() > 0 
                ? number_format(($completedResults->filter(function($result) {
                    return $result->score >= $result->quiz->passing_score;
                })->count() / $completedResults->count()) * 100, 1) . '%'
                : '0%'
        ];

        // Get recent attempts for the results section
        $recentAttempts = $results->take(5)->map(function($result) {
            return [
                'quiz_name' => $result->quiz->title,
                'score' => number_format($result->score, 1) . '%',
                'status' => $result->completed ? 'Completed' : 'In Progress',
                'attempted_at' => $result->created_at->diffForHumans(),
                'passed' => $result->completed && $result->score >= $result->quiz->passing_score
            ];
        });

        // Get available quizzes (not yet attempted or failed attempts)
        $availableQuizzes = Quiz::whereDoesntHave('results', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('completed', true)
                  ->whereRaw('score >= quizzes.passing_score');
        })
        ->where('is_published', true) // Assuming there's an is_published boolean column
        ->limit(5)
        ->get()
        ->map(function($quiz) {
            return [
                'title' => $quiz->title,
                'description' => $quiz->description,
                'question_count' => $quiz->questions->count(),
                'duration_minutes' => $quiz->duration,
                'difficulty' => $quiz->difficulty ?? 'medium' // Default to medium if not set
            ];
        });

        return view('user.dashboard', [
            'stats' => $stats,
            'recentAttempts' => $recentAttempts,
            'availableQuizzes' => $availableQuizzes
        ]);
    }
}
