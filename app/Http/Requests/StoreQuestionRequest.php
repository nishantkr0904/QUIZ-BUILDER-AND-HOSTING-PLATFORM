<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Question;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'question' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'in:multiple_choice,single_choice,true_false'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'explanation' => ['nullable', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];

        // Only require quiz_id for new questions
        if ($this->isMethod('POST')) {
            $rules['quiz_id'] = ['required', 'exists:quizzes,id'];
        }

        // Add conditional rules based on question type
        if ($this->type === 'true_false') {
            $rules['correct_answer'] = ['required', 'in:true,false'];
            $rules['options'] = ['prohibited'];
        } else {
            $rules['options'] = ['required', 'array', 'min:2', 'max:6'];
            $rules['options.*'] = ['required', 'string', 'distinct', 'max:255'];
            $rules['correct_answer'] = ['required', 'string'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'options.required' => 'Please provide answer options for the question.',
            'options.min' => 'Please provide at least 2 answer options.',
            'options.max' => 'You can only have up to 6 answer options.',
            'options.*.required' => 'Answer option cannot be empty.',
            'options.*.distinct' => 'Answer options must be unique.',
            'correct_answer.in' => 'For true/false questions, the correct answer must be either true or false.',
            'correct_answer.required' => 'Please select the correct answer.',
            'type.in' => 'Invalid question type selected.',
            'points.min' => 'Question must be worth at least 1 point.',
            'points.max' => 'Question cannot be worth more than 100 points.'
        ];
    }
}
