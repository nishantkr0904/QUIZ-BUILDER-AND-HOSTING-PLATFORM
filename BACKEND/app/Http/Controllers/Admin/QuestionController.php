<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display question management page for a quiz.
     */
    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions()
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();

        return view('admin.quizzes.questions', compact('quiz', 'questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Quiz $quiz)
    {
        $questionTypes = Question::getTypes();
        $nextOrder = $quiz->questions()->max('order') + 1;

        return view('admin.quizzes.questions.create', compact('quiz', 'questionTypes', 'nextOrder'));
    }

    /**
     * Store a newly created question.
     */
    public function store(StoreQuestionRequest $request)
    {
        $validated = $request->validated();
        
        $question = Question::create($validated);

        return redirect()
            ->route('admin.quizzes.questions.index', $question->quiz)
            ->with('success', 'Question added successfully.');
    }

    /**
     * Show the form for editing a question.
     */
    public function edit(Quiz $quiz, Question $question)
    {
        abort_if($question->quiz_id !== $quiz->id, 404);
        
        $questionTypes = Question::getTypes();
        
        return view('admin.quizzes.questions.edit', compact('quiz', 'question', 'questionTypes'));
    }

    /**
     * Update the specified question.
     */
    public function update(StoreQuestionRequest $request, Quiz $quiz, Question $question)
    {
        abort_if($question->quiz_id !== $quiz->id, 404);

        $validated = $request->validated();
        $question->update($validated);

        return redirect()
            ->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified question.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        abort_if($question->quiz_id !== $quiz->id, 404);
        
        $question->delete();

        return redirect()
            ->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Question deleted successfully.');
    }
    
    /**
     * Reorder questions.
     */
    public function reorder(Request $request, Quiz $quiz)
    {
        $request->validate([
            'questions' => ['required', 'array'],
            'questions.*' => ['required', 'integer', 'exists:questions,id']
        ]);

        $order = 0;
        foreach ($request->questions as $questionId) {
            Question::where('id', $questionId)
                ->where('quiz_id', $quiz->id)
                ->update(['order' => $order++]);
        }

        return response()->json(['message' => 'Questions reordered successfully']);
    }
}
