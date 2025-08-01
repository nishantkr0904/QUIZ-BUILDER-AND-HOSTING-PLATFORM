<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class InitializePageData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only initialize for routes that need quiz data
        if ($this->shouldInitializeData($request)) {
            $this->initializePageData();
        }

        return $next($request);
    }

    /**
     * Check if the current route needs quiz data initialization
     */
    protected function shouldInitializeData(Request $request): bool
    {
        $routes = ['home', 'dashboard', 'quiz.index'];
        return in_array($request->route()->getName(), $routes);
    }

    /**
     * Initialize necessary page data and cache
     */
    protected function initializePageData(): void
    {
        $userId = Auth::id() ?? 'guest';

        // Initialize featured quizzes if not cached
        if (!Cache::has(sprintf('featured_quizzes:%s', $userId))) {
            try {
                $featuredQuizzes = Quiz::with(['category', 'questions', 'creator'])
                    ->where('featured', true)
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where(function($query) {
                        $now = now();
                        $query->whereNull('availability_end')
                              ->orWhere('availability_end', '>', $now);
                    })
                    ->take(3)
                    ->get();

                Cache::put(sprintf('featured_quizzes:%s', $userId), $featuredQuizzes, 60);
            } catch (\Exception $e) {
                \Log::error('Failed to initialize featured quizzes', [
                    'error' => $e->getMessage(),
                    'user_id' => $userId
                ]);
            }
        }

        // Initialize categories if not cached
        if (!Cache::has(sprintf('active_categories:v1:%s', $userId))) {
            try {
                $categories = Category::withCount(['quizzes' => function($query) {
                    $query->where('status', 'published')
                          ->whereNotNull('published_at')
                          ->where(function($q) {
                              $now = now();
                              $q->whereNull('availability_end')
                                ->orWhere('availability_end', '>', $now);
                          });
                }])
                ->having('quizzes_count', '>', 0)
                ->orderBy('name')
                ->get();

                Cache::put(sprintf('active_categories:v1:%s', $userId), $categories, 300);
            } catch (\Exception $e) {
                \Log::error('Failed to initialize categories', [
                    'error' => $e->getMessage(),
                    'user_id' => $userId
                ]);
            }
        }

        // Initialize quiz stats if not cached
        $this->initializeQuizStats();
    }

    /**
     * Initialize quiz statistics with individual error handling
     */
    protected function initializeQuizStats(): void
    {
        if (!Cache::has('quiz_stats.total')) {
            try {
                $total = Quiz::where('status', 'published')->count();
                Cache::put('quiz_stats.total', $total, 300);
            } catch (\Exception $e) {
                \Log::error('Failed to initialize quiz total stats', ['error' => $e->getMessage()]);
            }
        }

        if (!Cache::has('quiz_stats.categories')) {
            try {
                $categoriesCount = Category::count();
                Cache::put('quiz_stats.categories', $categoriesCount, 300);
            } catch (\Exception $e) {
                \Log::error('Failed to initialize categories stats', ['error' => $e->getMessage()]);
            }
        }

        if (!Cache::has('quiz_stats.participants')) {
            try {
                $participants = \App\Models\User::whereHas('results')->count();
                Cache::put('quiz_stats.participants', $participants, 300);
            } catch (\Exception $e) {
                \Log::error('Failed to initialize participants stats', ['error' => $e->getMessage()]);
            }
        }
    }
}
