<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminQuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['category', 'questions'])
            ->withCount('questions')
            ->latest()
            ->paginate(10);
            
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.quizzes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'description' => 'required|min:10',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'featured' => 'boolean',
            'review_enabled' => 'boolean',
            'availability_start' => 'nullable|date',
            'availability_end' => 'nullable|date|after:availability_start'
        ]);

        $quiz = Quiz::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'created_by' => Auth::id(),
            'difficulty' => $request->difficulty,
            'description' => $request->description,
            'duration' => $request->duration,
            'passing_score' => $request->passing_score,
            'featured' => $request->featured ?? false,
            'review_enabled' => $request->review_enabled ?? false,
            'availability_start' => $request->availability_start,
            'availability_end' => $request->availability_end,
        ]);

        return redirect()->route('admin.quizzes.questions.create', $quiz->id)
            ->with('success', 'Quiz created successfully! Add some questions.');
    }

    public function edit($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $categories = Category::all();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        
        $request->validate([
            'title' => 'required|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'description' => 'required|min:10',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'featured' => 'boolean',
            'review_enabled' => 'boolean',
            'availability_start' => 'nullable|date',
            'availability_end' => 'nullable|date|after:availability_start'
        ]);

        $quiz->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty,
            'description' => $request->description,
            'duration' => $request->duration,
            'passing_score' => $request->passing_score,
            'featured' => $request->featured ?? false,
            'review_enabled' => $request->review_enabled ?? false,
            'availability_start' => $request->availability_start,
            'availability_end' => $request->availability_end,
        ]);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->questions()->delete();
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }

    public function questions($id)
    {
        $quiz = Quiz::with(['questions' => function($query) {
            $query->orderBy('order');
        }])->findOrFail($id);
        
        return view('admin.quizzes.questions', compact('quiz'));
    }

    public function createQuestion($id)
    {
        $quiz = Quiz::findOrFail($id);
        return view('admin.quizzes.questions.create', compact('quiz'));
    }

    public function storeQuestion(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        
        $request->validate([
            'question_text' => 'required|min:3',
            'question_type' => 'required|in:multiple_choice,true_false',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required|string|min:1',
            'correct_answer' => 'required',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string'
        ]);

        $lastOrder = $quiz->questions()->max('order') ?? 0;

        $quiz->questions()->create([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'options' => $request->question_type === 'multiple_choice' ? $request->options : ['True', 'False'],
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
            'explanation' => $request->explanation,
            'order' => $lastOrder + 1
        ]);

        return redirect()->route('admin.quizzes.questions', $quiz->id)
            ->with('success', 'Question added successfully!');
    }

    public function editQuestion($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);
        return view('admin.quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function updateQuestion(Request $request, $quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        
        $request->validate([
            'question_text' => 'required|min:3',
            'question_type' => 'required|in:multiple_choice,true_false',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required|string|min:1',
            'correct_answer' => 'required',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string'
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'options' => $request->question_type === 'multiple_choice' ? $request->options : ['True', 'False'],
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
            'explanation' => $request->explanation
        ]);

        return redirect()->route('admin.quizzes.questions', $quizId)
            ->with('success', 'Question updated successfully!');
    }

    public function destroyQuestion($quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();

        return redirect()->route('admin.quizzes.questions', $quizId)
            ->with('success', 'Question deleted successfully!');
    }

    public function reorderQuestions(Request $request, $id)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id'
        ]);

        foreach($request->questions as $index => $questionId) {
            Question::where('id', $questionId)->update(['order' => $index + 1]);
        }

        return response()->json(['message' => 'Questions reordered successfully']);
    }
}
