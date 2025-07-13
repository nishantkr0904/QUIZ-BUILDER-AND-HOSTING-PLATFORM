<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|string',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0',
            'review_enabled' => 'boolean',
        ];
    }
}
