<?php
// Quiz model with relationships
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category_id', 'created_by', 'difficulty', 'duration',
        'passing_score', 'review_enabled', 'availability_start', 'availability_end',
        'featured', 'description', 'status'
    ];

    protected $casts = [
        'review_enabled' => 'boolean',
        'availability_start' => 'datetime',
        'availability_end' => 'datetime',
        'duration' => 'integer',
        'passing_score' => 'integer',
        'featured' => 'boolean'
    ];
    
    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = is_numeric($value) ? intval($value) : 30; // Default 30 minutes if invalid
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDifficultyColorAttribute()
    {
        return [
            'easy' => 'success',
            'medium' => 'warning',
            'hard' => 'danger'
        ][$this->difficulty] ?? 'secondary';
    }

    public function isAvailable()
    {
        $now = now();
        
        if ($this->availability_start && $now < $this->availability_start) {
            return false;
        }
        
        if ($this->availability_end && $now > $this->availability_end) {
            return false;
        }
        
        return true;
    }

    public function scopeAvailable($query)
    {
        $now = now();
        return $query->where(function($q) use ($now) {
            $q->whereNull('availability_start')
              ->orWhere('availability_start', '<=', $now);
        })->where(function($q) use ($now) {
            $q->whereNull('availability_end')
              ->orWhere('availability_end', '>=', $now);
        });
    }

    public function getProgressForUser($userId)
    {
        $result = $this->results()
            ->where('user_id', $userId)
            ->where('completed', false)
            ->latest()
            ->first();
            
        if (!$result) {
            return null;
        }
        
        return [
            'answered_count' => $result->answered_questions_count,
            'total_questions' => $this->questions()->count(),
            'percentage' => $this->questions()->count() > 0 
                ? round(($result->answered_questions_count / $this->questions()->count()) * 100, 1) 
                : 0
        ];
    }
}
