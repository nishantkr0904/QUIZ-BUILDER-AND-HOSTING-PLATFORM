<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Result;
use App\Services\ScoringService;
use Illuminate\Support\Facades\Auth;

class QuizAjaxController extends Controller
{
    // Save answer dynamically (AJAX)
    public function saveAnswer(Request $request, $quizId)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required',
        ]);
        $userId = Auth::id();
        $key = "quiz_{$quizId}_user_{$userId}_answers";
        $answers = session($key, []);
        $answers[$request->question_id] = $request->answer;
        session([$key => $answers]);
        return response()->json(['status' => 'saved']);
    }

    // Submit quiz (AJAX)
    public function submit(Request $request, $quizId, ScoringService $scoringService)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $userId = Auth::id();
        $key = "quiz_{$quizId}_user_{$userId}_answers";
        $answers = session($key, []);
        $result = $scoringService->calculateScore($quiz, $answers);
        Result::create([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'score' => $result['score'],
            'details' => $result['details'],
        ]);
        session()->forget($key);
        return response()->json(['status' => 'submitted', 'score' => $result['score'], 'details' => $result['details']]);
    }
}
