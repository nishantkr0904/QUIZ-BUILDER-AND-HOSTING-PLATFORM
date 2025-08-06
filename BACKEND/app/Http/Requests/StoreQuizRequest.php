<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'category_id' => ['required', 'exists:categories,id'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'duration' => ['required', 'integer', 'min:1', 'max:480'], // Max 8 hours
            'passing_score' => ['required', 'integer', 'min:1', 'max:100'],
            'review_enabled' => ['boolean'],
            'display_mode' => ['required', 'in:one_by_one,full_form'],
            'randomize_questions' => ['boolean'],
            'availability_start' => ['nullable', 'date', 'after_or_equal:today'],
            'availability_end' => ['nullable', 'date', 'after:availability_start']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a quiz title.',
            'description.required' => 'Please provide a description for the quiz.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'difficulty.required' => 'Please select a difficulty level.',
            'difficulty.in' => 'The selected difficulty level is invalid.',
            'duration.required' => 'Please specify the quiz duration.',
            'duration.integer' => 'Duration must be a whole number.',
            'duration.min' => 'Duration must be at least 1 minute.',
            'duration.max' => 'Duration cannot exceed 8 hours (480 minutes).',
            'passing_score.required' => 'Please specify the passing score percentage.',
            'passing_score.integer' => 'Passing score must be a whole number.',
            'passing_score.min' => 'Passing score must be at least 1%.',
            'passing_score.max' => 'Passing score cannot exceed 100%.',
            'display_mode.required' => 'Please select how questions should be displayed.',
            'display_mode.in' => 'Invalid display mode selected.',
            'availability_start.date' => 'Invalid start date format.',
            'availability_start.after_or_equal' => 'Start date must be today or later.',
            'availability_end.date' => 'Invalid end date format.',
            'availability_end.after' => 'End date must be after start date.'
        ];
    }
}
