<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'difficulty',
        'duration',
        'passing_score',
        'review_enabled',
        'availability_start',
        'availability_end',
        'created_by',
        'display_mode',
        'randomize_questions',
        'status'
    ];

    protected $casts = [
        'review_enabled' => 'boolean',
        'duration' => 'integer',
        'passing_score' => 'integer',
        'availability_start' => 'datetime',
        'availability_end' => 'datetime',
        'randomize_questions' => 'boolean'
    ];

    /**
     * Get the questions for the quiz.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the category that owns the quiz.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who created the quiz.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the results for the quiz.
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Scope a query to only include published quizzes.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Get the HTML badge for the quiz difficulty.
     */
    public function getDifficultyBadgeAttribute()
    {
        $colors = [
            'easy' => 'success',
            'medium' => 'warning',
            'hard' => 'danger'
        ];

        return '<span class="badge bg-' . ($colors[$this->difficulty] ?? 'secondary') . '">'
             . ucfirst($this->difficulty)
             . '</span>';
    }

    /**
     * Get the formatted duration string.
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->duration < 60) {
            return $this->duration . ' minutes';
        }
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        return $hours . 'h ' . ($minutes > 0 ? $minutes . 'm' : '');
    }
}
