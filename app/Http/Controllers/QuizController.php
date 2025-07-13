<?php
namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('category')->get();
        return view('quizzes.list', compact('quizzes'));
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('quizzes.take', compact('quiz'));
    }

    public function result($id)
    {
        // Placeholder for result logic
        return view('quizzes.result');
    }
}
