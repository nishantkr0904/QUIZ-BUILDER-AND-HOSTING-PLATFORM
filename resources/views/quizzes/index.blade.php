@extends('layouts.app')

@section('title', 'Quiz Platform')

@section('content')
<div class="container py-5">
    <!-- Start a Quiz Section -->
    <div class="d-flex align-items-center mb-4">
        <h2 class="h4 mb-0 me-auto">Available Quizzes</h2>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        @else
            <div>
                <a href="{{ route('login') }}" class="btn btn-primary me-2">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-success">Register</a>
            </div>
        @endauth
    </div>

    <!-- Featured Quizzes Section -->
    @if($featuredQuizzes->isNotEmpty())
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="h5 mb-3">Featured Quizzes</h3>
            <div class="row g-3">
                @foreach($featuredQuizzes as $quiz)
                    <div class="col-md-4">
                        <div class="card h-100 quiz-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                                    <span class="badge bg-{{ $quiz->difficulty_color }}">
                                        {{ ucfirst($quiz->difficulty) }}
                                    </span>
                                </div>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($quiz->description, 80) }}</p>
                                <a href="{{ route('quiz.take', $quiz->id) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-play-circle me-1"></i> Start Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Categories Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="h5 mb-3">Categories</h3>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ request()->url() }}" 
                   class="btn btn-outline-primary btn-sm {{ !request('category') ? 'active' : '' }}">
                    All Categories
                </a>
                @foreach($categories as $category)
                    <a href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}" 
                       class="btn btn-outline-primary btn-sm {{ request('category') == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                        <span class="badge bg-primary ms-1">{{ $category->quizzes_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Difficulty Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="h5 mb-3">Difficulty</h3>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ request()->url() }}" 
                   class="btn btn-outline-secondary btn-sm {{ !request('difficulty') ? 'active' : '' }}">
                    All Levels
                </a>
                @foreach(['easy', 'medium', 'hard'] as $level)
                    <a href="{{ request()->fullUrlWithQuery(['difficulty' => $level]) }}" 
                       class="btn btn-outline-{{ $level === 'easy' ? 'success' : ($level === 'medium' ? 'warning' : 'danger') }} btn-sm 
                          {{ request('difficulty') === $level ? 'active' : '' }}">
                        {{ ucfirst($level) }}
                        <span class="badge bg-{{ $level === 'easy' ? 'success' : ($level === 'medium' ? 'warning' : 'danger') }} ms-1">
                            {{ $quizzes->where('difficulty', $level)->count() }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quiz List -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($quizzes as $quiz)
            <div class="col">
                <div class="card h-100 quiz-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="card-title h5 mb-0">{{ $quiz->title }}</h4>
                            <span class="badge bg-{{ $quiz->difficulty_color }}">
                                {{ ucfirst($quiz->difficulty) }}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted mb-3">{{ Str::limit($quiz->description, 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="small text-muted">
                                <i class="fas fa-clock me-1"></i> {{ $quiz->duration }} minutes
                                <span class="mx-2">•</span>
                                <i class="fas fa-question-circle me-1"></i> {{ $quiz->questions_count }} questions
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('quiz.take', $quiz->id) }}" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-play-circle me-1"></i> Start Quiz
                            </a>
                            @if($quiz->results_count > 0)
                                <button type="button" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Times Attempted">
                                    <i class="fas fa-history me-1"></i> {{ $quiz->results_count }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No quizzes found matching your criteria. Try adjusting your filters.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection
