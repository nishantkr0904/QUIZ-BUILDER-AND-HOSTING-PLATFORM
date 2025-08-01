<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'completed_at',
        'time_taken',
        'status'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'time_taken' => 'integer',
        'score' => 'float'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
