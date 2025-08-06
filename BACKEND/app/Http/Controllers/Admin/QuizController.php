<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreQuizRequest;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of quizzes
     */
    public function index()
    {
        $quizzes = Quiz::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.quizzes.create', compact('categories'));
    }

    /**
     * Store a newly created quiz
     */
    public function store(StoreQuizRequest $request)
    {
        try {
            DB::beginTransaction();

            $quiz = Quiz::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'category_id' => $request->category_id,
                'difficulty' => $request->difficulty,
                'duration' => $request->duration,
                'passing_score' => $request->passing_score,
                'review_enabled' => $request->boolean('review_enabled'),
                'display_mode' => $request->display_mode,
                'randomize_questions' => $request->boolean('randomize_questions'),
                'availability_start' => $request->availability_start,
                'availability_end' => $request->availability_end,
                'created_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()
                ->route('admin.quizzes.edit', $quiz)
                ->with('success', 'Quiz created successfully! You can now add questions.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Quiz Creation Error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create quiz. Please try again.');
        }
    }

    /**
     * Display the specified quiz
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['category', 'questions']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified quiz
     */
    public function edit(Quiz $quiz)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    /**
     * Update quiz details
     */
    public function update(StoreQuizRequest $request, Quiz $quiz)
    {
        try {
            $quiz->update([
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'difficulty' => $request->difficulty,
                'duration' => $request->duration,
                'passing_score' => $request->passing_score,
                'review_enabled' => $request->boolean('review_enabled')
            ]);

            return redirect()
                ->route('admin.quizzes.edit', $quiz)
                ->with('success', 'Quiz updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Quiz Update Error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update quiz. Please try again.');
        }
    }

    /**
     * Remove the specified quiz
     */
    public function destroy(Quiz $quiz)
    {
        try {
            $quiz->delete();
            return redirect()
                ->route('admin.quizzes')
                ->with('success', 'Quiz deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Quiz Deletion Error: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Failed to delete quiz. Please try again.');
        }
    }
}
