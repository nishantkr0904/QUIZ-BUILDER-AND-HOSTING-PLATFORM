<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Category;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of quizzes.
     */
    public function index()
    {
        $quizzes = Quiz::with('category')
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('admin.quizzes.index', compact('quizzes', 'categories'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.quizzes.create', compact('categories'));
    }

    /**
     * Store a newly created quiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'time_limit' => 'required|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100'
        ]);

        $quiz = Quiz::create($validated);

        return redirect()
            ->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Quiz created successfully. Now add some questions!');
    }

    /**
     * Show quiz details.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['category', 'questions']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing a quiz.
     */
    public function edit(Quiz $quiz)
    {
        $quiz->load('questions');
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    /**
     * Update the specified quiz.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'display_mode' => 'required|in:one_by_one,full_form',
            'availability_start' => 'nullable|date',
            'availability_end' => 'nullable|date|after:availability_start',
            'review_enabled' => 'boolean',
            'randomize_questions' => 'boolean'
        ]);

        // Handle boolean fields properly
        $validated['review_enabled'] = $request->boolean('review_enabled');
        $validated['randomize_questions'] = $request->boolean('randomize_questions');

        $quiz->update($validated);

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified quiz.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->questions()->delete();
        $quiz->delete();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }
}
