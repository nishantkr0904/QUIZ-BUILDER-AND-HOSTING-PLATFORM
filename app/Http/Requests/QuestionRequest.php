<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }
    public function rules()
    {
        return [
            'quiz_id' => 'required|exists:quizzes,id',
            'type' => 'required|in:MCQ,single,true_false',
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
        ];
    }
}
