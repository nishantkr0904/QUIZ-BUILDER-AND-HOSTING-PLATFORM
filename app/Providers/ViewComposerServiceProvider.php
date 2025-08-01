<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share default values with home view
        View::composer('home', function ($view) {
            $defaults = [
                'featuredQuizzes' => collect([]),
                'categories' => collect([]),
                'filteredQuizzes' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]), 0, 9, 1
                ),
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

            // Only set variables that haven't been set yet
            foreach ($defaults as $key => $value) {
                if (!$view->offsetExists($key)) {
                    $view->with($key, $value);
                }
            }
        });
    }
}
