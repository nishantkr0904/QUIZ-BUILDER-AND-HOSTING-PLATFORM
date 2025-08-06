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
            'quiz_id' => ['required', 'exists:quizzes,id'],
            'question' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'in:multiple_choice,single_choice,true_false'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'explanation' => ['nullable', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];

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
            'quiz_id.required' => 'Quiz ID is required.',
            'quiz_id.exists' => 'Selected quiz does not exist.',
            'question.required' => 'Question text is required.',
            'question.max' => 'Question text cannot exceed 1000 characters.',
            'type.required' => 'Question type is required.',
            'type.in' => 'Invalid question type selected.',
            'options.required' => 'Options are required for this question type.',
            'options.array' => 'Options must be provided as a list.',
            'options.min' => 'At least 2 options are required.',
            'options.max' => 'Maximum 6 options are allowed.',
            'options.*.required' => 'Option text is required.',
            'options.*.distinct' => 'Options must be unique.',
            'options.*.max' => 'Option text cannot exceed 255 characters.',
            'correct_answer.required' => 'Correct answer is required.',
            'correct_answer.in' => 'Correct answer must be true or false.',
            'points.required' => 'Points value is required.',
            'points.integer' => 'Points must be a whole number.',
            'points.min' => 'Points must be at least 1.',
            'points.max' => 'Points cannot exceed 100.',
            'explanation.max' => 'Explanation cannot exceed 1000 characters.',
            'order.integer' => 'Order must be a whole number.',
            'order.min' => 'Order cannot be negative.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->type === 'true_false') {
            $this->merge([
                'options' => null
            ]);
        }
    }
}
