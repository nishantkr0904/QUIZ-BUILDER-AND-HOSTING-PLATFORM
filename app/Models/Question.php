<?php
// Question model with relationships
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'type', 'question_text', 'options', 'correct_answer', 'points',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
