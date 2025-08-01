<?php
namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show the quiz details page or start quiz based on the flow
     */
    public function show($id)
    {
        $quiz = Quiz::with(['questions' => function($q) {
            $q->orderBy('order');
        }, 'category'])->findOrFail($id);
        
        // First check if this is a details view request
        if (request()->has('details')) {
            return view('quiz.show', compact('quiz'));
        }
        
        // Check if user has an incomplete attempt
        $incompleteAttempt = $quiz->results()
            ->where('user_id', Auth::id())
            ->where('completed', false)
            ->latest()
            ->first();
            
        if ($incompleteAttempt) {
            return redirect()->route('quiz.resume', $quiz->id);
        }
        
        return view('quizzes.take', compact('quiz'));
    }

    /**
     * Start a quiz session
     */
    public function start(Quiz $quiz)
    {
        // Initialize quiz session
        session(['quiz_id' => $quiz->id, 'start_time' => now()]);
        
        return redirect()->route('quiz.take', ['id' => $quiz->id]);
    }

    /**
     * Show the quiz taking interface
     */
    public function take($id)
    {
        $quiz = Quiz::with(['questions' => function($q) {
            $q->orderBy('order');
        }])->findOrFail($id);

        // Verify that a quiz session exists
        if (!session('quiz_id') || session('quiz_id') != $id) {
            return redirect()->route('quiz.show', ['id' => $id, 'details' => true])
                ->with('error', 'Please start the quiz from the details page.');
        }

        return view('quizzes.take', compact('quiz'));
    }

    /**
     * Display a listing of available quizzes grouped by category and difficulty.
     * Implements FR003 requirements for the home page.
     */
    public function index(Request $request)
    {
        try {
            // Get all categories with their published quizzes
            $categories = Category::with(['quizzes' => function($query) {
                $query->where('status', 'published')
                      ->orderBy('difficulty')
                      ->orderBy('created_at', 'desc')
                      ->with('questions'); // Eager load questions for count
            }])->get();

            // Prepare quiz statistics
            $publishedQuizzes = Quiz::where('status', 'published')->get();
            $quizStats = [
                'total_quizzes' => $publishedQuizzes->count(),
                'total_categories' => Category::count(),
                'total_participants' => \App\Models\User::whereHas('results')->count()
            ];
            return view('home', compact('categories', 'quizStats'));

            // Log search queries for analytics
            if ($search) {
                Log::info('Quiz search', [
                    'query' => $search,
                    'user_id' => Auth::id() ?? 'guest',
                    'filters' => [
                        'category' => $selectedCategory,
                        'difficulty' => $selectedDifficulty,
                        'sort' => $sort
                    ]
                ]);
            }

            try {
                // Get featured quizzes with eager loading and cache based on auth state
                $cacheKey = sprintf('featured_quizzes:%s', Auth::id() ?? 'guest');
                $featuredQuizzes = Cache::remember($cacheKey, 60, function () {
                    return Quiz::with(['category', 'questions', 'creator'])
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
                });

                // Clear cache if no featured quizzes found
                if ($featuredQuizzes->isEmpty()) {
                    Cache::forget($cacheKey);
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch featured quizzes', [
                    'error' => $e->getMessage(),
                    'user_id' => Auth::id() ?? 'guest'
                ]);
                $featuredQuizzes = collect([]);
            }

            // If no featured quizzes, get latest published quizzes
            if ($featuredQuizzes->isEmpty()) {
                $featuredQuizzes = Quiz::with(['category', 'questions', 'creator'])
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->latest()
                    ->take(3)
                    ->get();
            }

            try {
                // Get categories with active quiz count with versioned cache key
                $cacheKey = sprintf('active_categories:v1:%s', Auth::id() ?? 'guest');
                $categories = Cache::remember($cacheKey, 300, function () {
                    return Category::withCount(['quizzes' => function($query) {
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
                });

                // Clear cache if no categories found
                if ($categories->isEmpty()) {
                    Cache::forget($cacheKey);
                    $categories = collect([]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch categories', [
                    'error' => $e->getMessage(),
                    'user_id' => Auth::id() ?? 'guest'
                ]);
                $categories = collect([]);
            }

            // Build main quiz query with eager loading
            $query = Quiz::with(['category', 'questions', 'creator'])
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where(function($query) {
                    $now = now();
                    $query->whereNull('availability_end')
                          ->orWhere('availability_end', '>', $now);
                });

            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Apply category filter
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory);
            }

            // Apply difficulty filter
            if ($selectedDifficulty) {
                $query->where('difficulty', $selectedDifficulty);
            }

            // Apply sorting
            switch ($sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'most_attempted':
                    $query->withCount('results')->orderByDesc('results_count');
                    break;
                case 'highest_rated':
                    $query->withAvg('results', 'rating')->orderByDesc('results_avg_rating');
                    break;
                default: // newest
                    $query->latest();
            }

            try {
                // Get paginated quizzes with counts and cache if needed
                $cacheKey = sprintf('filtered_quizzes:%s:%s:%s:%s:%s', 
                    $selectedCategory ?? 'all',
                    $selectedDifficulty ?? 'all',
                    $sort,
                    $search ?? 'none',
                    $request->get('page', 1)
                );

                $quizzes = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                    return $query->withCount(['questions', 'results'])
                        ->paginate(9)
                        ->appends($request->query());
                });

                // Get total quiz stats with individual caching for better resilience
                $quizStats = [
                    'total' => Cache::remember('quiz_stats.total', 300, function() {
                        try {
                            return Quiz::where('status', 'published')->count();
                        } catch (\Exception $e) {
                            Log::error('Failed to get total quiz count', ['error' => $e->getMessage()]);
                            return 0;
                        }
                    }),
                    'categories' => Cache::remember('quiz_stats.categories', 300, function() {
                        try {
                            return Category::count();
                        } catch (\Exception $e) {
                            Log::error('Failed to get categories count', ['error' => $e->getMessage()]);
                            return 0;
                        }
                    }),
                    'participants' => Cache::remember('quiz_stats.participants', 300, function() {
                        try {
                            return \App\Models\User::whereHas('results')->count();
                        } catch (\Exception $e) {
                            Log::error('Failed to get participants count', ['error' => $e->getMessage()]);
                            return 0;
                        }
                    })
                ];

                // Return view with data and metadata
                return view('home', compact(
                    'featuredQuizzes',
                    'categories',
                    'quizzes',
                    'selectedCategory',
                    'selectedDifficulty',
                    'sort',
                    'search',
                    'quizStats'
                ));

            } catch (\Exception $e) {
                Log::error('Failed to fetch filtered quizzes', [
                    'error' => $e->getMessage(),
                    'filters' => [
                        'category' => $selectedCategory,
                        'difficulty' => $selectedDifficulty,
                        'sort' => $sort,
                        'search' => $search
                    ]
                ]);

                // Return empty paginator with error message
                $quizzes = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]), 0, 9, 1
                );

                // Set default quiz stats in error case
                $quizStats = [
                    'total' => Cache::get('quiz_stats.total', 0),
                    'categories' => Cache::get('quiz_stats.categories', 0),
                    'participants' => Cache::get('quiz_stats.participants', 0)
                ];

                return view('home', compact(
                    'featuredQuizzes',
                    'categories',
                    'quizzes',
                    'selectedCategory',
                    'selectedDifficulty',
                    'sort',
                    'search',
                    'quizStats'
                ))->with('error', 'Failed to load quizzes. Please try again.');
            }
            
        } catch (\Exception $e) {
            report($e); // Log the error
            
            // Return graceful fallback with empty data
            $categories = collect([]);
            $quizStats = [
                'total_quizzes' => 0,
                'total_categories' => 0,
                'total_participants' => 0
            ];
            
            return view('home', compact('categories', 'quizStats'))
                ->with('error', 'Unable to load quiz data. Please try again later.');
        }
    }

    public function available()
    {
        $user = Auth::user();
        $quizzes = Quiz::with(['category', 'questions'])
            ->whereDoesntHave('results', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', '=', 'published')
            ->where(function($query) {
                $now = now();
                $query->whereNull('availability_start')
                      ->orWhere('availability_start', '<=', $now);
            })
            ->where(function($query) {
                $now = now();
                $query->whereNull('availability_end')
                      ->orWhere('availability_end', '>=', $now);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('user.available-quizzes', compact('quizzes'));
    }



    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        
        // Create or update quiz result
        $result = $quiz->results()->create([
            'user_id' => Auth::id(),
            'started_at' => $request->start_time,
            'completed_at' => now(),
            'time_taken' => $request->time_taken,
            'completed' => true
        ]);
        
        // Process answers and calculate score
        $score = 0;
        $details = [];
        
        foreach($quiz->questions as $question) {
            $userAnswer = $request->input('answers.' . $question->id);
            $isCorrect = $userAnswer == $question->correct_answer;
            
            if($isCorrect) {
                $score += $question->points;
            }
            
            $details[$question->id] = [
                'question_text' => $question->question_text,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'points' => $isCorrect ? $question->points : 0
            ];
        }
        
        // Update result with score and details
        $result->update([
            'score' => $score,
            'details' => $details
        ]);
        
        return redirect()->route('quiz.result', ['id' => $quiz->id])
            ->with('success', 'Quiz completed successfully!');
    }

    public function result($id)
    {
        $quiz = Quiz::findOrFail($id);
        
        // Get the latest result for this quiz, completed or not
        $result = $quiz->results()
            ->where('user_id', Auth::id())
            ->latest()
            ->first();
            
        // If no result exists or quiz is not completed, redirect to take the quiz
        if (!$result || !$result->completed) {
            return redirect()->route('quiz.take', ['id' => $quiz->id])
                ->with('info', 'Please complete the quiz to see your results.');
        }
        
        return view('quizzes.result', compact('quiz', 'result'));
    }

    public function resume($id)
    {
        $quiz = Quiz::with(['questions' => function($q) {
            $q->orderBy('order');
        }])->findOrFail($id);
        
        $result = $quiz->results()
            ->where('user_id', Auth::id())
            ->where('completed', false)
            ->latest()
            ->firstOrFail();
            
        return view('quizzes.resume', compact('quiz', 'result'));
    }

    // Admin Methods
    public function create()
    {
        $categories = Category::all();
        return view('admin.quizzes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0',
            'review_enabled' => 'boolean',
            'availability_start' => 'nullable|date',
            'availability_end' => 'nullable|date|after:availability_start'
        ]);

        $validated['created_by'] = Auth::id();
        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.questions', $quiz->id)
            ->with('success', 'Quiz created successfully. Now add some questions!');
    }

    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        $categories = Category::all();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0',
            'review_enabled' => 'boolean',
            'availability_start' => 'nullable|date',
            'availability_end' => 'nullable|date|after:availability_start'
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes')
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('admin.quizzes')
            ->with('success', 'Quiz deleted successfully.');
    }
}
