<?php

namespace App\Http\View\Composers;

use App\Models\Quiz;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeViewComposer
{
    public function compose(View $view)
    {
        // Set default values for all required variables
        $defaults = [
            'featuredQuizzes' => collect([]),
            'categories' => collect([]),
            'quizzes' => null, // Will be populated by controller
            'selectedCategory' => null,
            'selectedDifficulty' => null,
            'sort' => 'newest',
            'search' => '',
            'quizStats' => [
                'total' => 0,
                'categories' => 0,
                'participants' => 0
            ]
        ];

        // Merge our defaults with any existing view data
        $view->with($defaults);
    }
}
