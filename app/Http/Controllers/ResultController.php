<?php
namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $completedQuizzes = Result::where('user_id', Auth::id())
            ->where('completed', true)
            ->with(['quiz' => function($query) {
                $query->withCount('questions');
            }])
            ->orderBy('completed_at', 'desc')
            ->get();
            
        $inProgressQuizzes = Result::where('user_id', Auth::id())
            ->where('completed', false)
            ->with(['quiz' => function($query) {
                $query->withCount('questions');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('user.quizzes', compact('completedQuizzes', 'inProgressQuizzes'));
    }

    public function results()
    {
        $results = Result::where('user_id', Auth::id())
            ->where('completed', true)
            ->with(['quiz' => function($query) {
                $query->withCount('questions');
            }])
            ->orderBy('completed_at', 'desc')
            ->get();
            
        return view('user.results', compact('results'));
    }

    public function show(Result $result)
    {
        // Ensure the user can only view their own results
        if ($result->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('quizzes.result', [
            'quiz' => $result->quiz,
            'result' => $result,
            'answers' => $result->answers,
            'score' => $result->score,
            'completed_at' => $result->completed_at
        ]);
    }
}
