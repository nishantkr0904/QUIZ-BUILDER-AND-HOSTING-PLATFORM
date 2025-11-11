<?php
// Question model with relationships
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'text', 'type', 'options', 'correct_answer', 'explanation', 'order'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get available question types.
     *
     * @return array
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
