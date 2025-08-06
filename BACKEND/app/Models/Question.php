<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'options',
        'correct_answer',
        'explanation',
        'points',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer',
        'order' => 'integer'
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get available question types.
     */
    public static function getTypes(): array
    {
        return [
            'multiple_choice' => 'Multiple Choice',
            'single_choice' => 'Single Choice',
            'true_false' => 'True/False'
        ];
    }
}
