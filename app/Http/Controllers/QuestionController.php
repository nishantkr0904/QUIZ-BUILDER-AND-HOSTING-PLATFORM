<?php
namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index($quiz_id)
    {
        $quiz = Quiz::with(['questions' => function($query) {
            $query->orderBy('order');
        }])->findOrFail($quiz_id);
        return view('admin.questions.index', compact('quiz'));
    }

    public function create($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        return view('admin.questions.create', compact('quiz'));
    }

    public function store(Request $request, $quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,single_answer,true_false',
            'options' => 'required_unless:question_type,true_false|array|min:2',
            'correct_answer' => 'required',
            'explanation' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'order' => 'nullable|integer'
        ]);

        // Set the order to the end if not specified
        if (!isset($validated['order'])) {
            $validated['order'] = $quiz->questions()->max('order') + 1;
        }

        $question = new Question($validated);
        $quiz->questions()->save($question);

        return redirect()->route('admin.questions', $quiz->id)
            ->with('success', 'Question added successfully');
    }

    public function edit($id)
    {
        $question = Question::with('quiz')->findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,single_answer,true_false',
            'options' => 'required_unless:question_type,true_false|array|min:2',
            'correct_answer' => 'required',
            'explanation' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'order' => 'nullable|integer'
        ]);

        $question->update($validated);

        return redirect()->route('admin.questions', $question->quiz_id)
            ->with('success', 'Question updated successfully');
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $quiz_id = $question->quiz_id;
        $question->delete();

        return redirect()->route('admin.questions', $quiz_id)
            ->with('success', 'Question deleted successfully');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|integer|exists:questions,id'
        ]);

        foreach ($validated['questions'] as $order => $question_id) {
            Question::where('id', $question_id)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }
}
