<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Quiz;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Initialize cache for guest users
     */
    protected function initializeGuestCache()
    {
        try {
            // Initialize basic data for guests
            Cache::remember('featured_quizzes:guest', 60, function () {
                return Quiz::with(['category', 'questions'])
                    ->where('featured', true)
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->take(3)
                    ->get();
            });

            Cache::remember('active_categories:v1:guest', 300, function () {
                return Category::withCount(['quizzes' => function($query) {
                    $query->where('status', 'published')
                          ->whereNotNull('published_at');
                }])
                ->having('quizzes_count', '>', 0)
                ->orderBy('name')
                ->get();
            });

            Cache::remember('quiz_stats:guest', 300, function () {
                return [
                    'total' => Quiz::where('status', 'published')->count(),
                    'categories' => Category::count(),
                    'participants' => User::whereHas('results')->count()
                ];
            });
        } catch (\Exception $e) {
            Log::error('Failed to initialize guest cache', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // Initialize necessary data before redirecting
            if (Auth::check()) {
                $userId = Auth::id();
                
                // Clear old cache first
                $this->clearUserCache($userId);
                
                // Pre-warm the cache with essential data
                $this->initializeUserCache($userId);
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            
            return back()->withErrors([
                'email' => 'Authentication failed. Please try again.',
            ])->withInput($request->except('password'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {
            // Get user ID before logout for cache clearing
            $userId = Auth::id();

            // Clear user-specific cache before logout
            if ($userId) {
                $this->clearUserCache($userId);
            }

            // Perform logout
            Auth::guard('web')->logout();

            // Clear and regenerate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Initialize guest cache
            $this->initializeGuestCache();

            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'user_id' => $userId ?? 'unknown'
            ]);

            // Even if there's an error, ensure the user is logged out
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('warning', 'You have been logged out, but some cleanup operations failed.');
        }
    }

    /**
     * Clear user-specific cache.
     */
    protected function clearUserCache($userId)
    {
        try {
            // Clear all user-specific caches
            $cacheKeys = [
                sprintf('featured_quizzes:%s', $userId),
                sprintf('active_categories:v1:%s', $userId),
                sprintf('quiz_stats:%s', $userId),
                sprintf('user_dashboard:%s', $userId),
                sprintf('user_progress:%s', $userId)
            ];

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }

            // Clear filtered quizzes cache patterns
            if (method_exists(Cache::getStore(), 'tags')) {
                try {
                    Cache::tags(['user_quizzes_' . $userId])->flush();
                } catch (\Exception $e) {
                    Log::warning('Failed to clear tagged cache', [
                        'error' => $e->getMessage(),
                        'user_id' => $userId
                    ]);
                }
            }

            // Clear any paginated results
            for ($page = 1; $page <= 5; $page++) {
                Cache::forget(sprintf('filtered_quizzes:%s:all:all:newest:none:%d', $userId, $page));
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear user cache', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
        }
    }

    /**
     * Initialize cache for a logged-in user.
     */
    protected function initializeUserCache($userId)
    {
        try {
            // Initialize featured quizzes
            Cache::remember(sprintf('featured_quizzes:%s', $userId), 60, function () {
                return Quiz::with(['category', 'questions'])
                    ->where('featured', true)
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->take(3)
                    ->get();
            });

            // Initialize active categories
            Cache::remember(sprintf('active_categories:v1:%s', $userId), 300, function () {
                return Category::withCount(['quizzes' => function($query) {
                    $query->where('status', 'published')
                          ->whereNotNull('published_at');
                }])
                ->having('quizzes_count', '>', 0)
                ->orderBy('name')
                ->get();
            });

            // Initialize quiz stats
            Cache::remember(sprintf('quiz_stats:%s', $userId), 300, function () {
                return [
                    'total' => Quiz::where('status', 'published')->count(),
                    'categories' => Category::count(),
                    'participants' => User::whereHas('results')->count()
                ];
            });

            // Pre-warm first page of quizzes
            Cache::remember(
                sprintf('filtered_quizzes:%s:all:all:newest:none:1', $userId),
                60,
                function () {
                    return Quiz::with(['category', 'questions'])
                        ->where('status', 'published')
                        ->whereNotNull('published_at')
                        ->latest()
                        ->paginate(9);
                }
            );

        } catch (\Exception $e) {
            Log::error('Failed to initialize user cache', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
        }
    }
}
