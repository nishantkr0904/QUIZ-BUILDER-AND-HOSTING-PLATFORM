@extends('layouts.app')

@section('title', 'Home - Quiz Builder and Hosting Platform')

@push('styles')
<style>
    .category-section {
        margin-bottom: 3rem;
    }
    
    .difficulty-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }
    
    .quiz-card {
        transition: transform 0.2s ease-in-out;
        height: 100%;
        border: 1px solid #e9ecef;
    }
    
    .quiz-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .quiz-info {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .category-header {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
    }

    .empty-category {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
    }

    .btn-outline-info {
        color: #0dcaf0;
        border: 1px solid #0dcaf0;
        background-color: transparent;
    }
    
    .btn-outline-info:hover {
        color: #fff;
        background-color: #0dcaf0;
    }
    
    .gap-3 {
        gap: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 mb-3">Welcome to Quiz Platform</h1>
        <p class="lead text-muted">Choose from {{ $quizStats['total_quizzes'] }} quizzes across {{ $quizStats['total_categories'] }} categories</p>
    </div>

    <!-- Categories and Quizzes -->
    @forelse($categories as $category)
        @if($category->quizzes->count() > 0)
            <div class="category-section mb-5">
                <div class="category-header d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">{{ $category->name }}</h2>
                    <span class="badge bg-secondary">{{ $category->quizzes->count() }} {{ Str::plural('quiz', $category->quizzes->count()) }}</span>
                </div>
                
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3">
                    @foreach($category->quizzes as $quiz)
                        <div class="col">
                            <div class="card quiz-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                                        <span class="badge bg-{{ $quiz->difficulty == 'easy' ? 'success' : ($quiz->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($quiz->difficulty) }}
                                        </span>
                                    </div>
                                    
                                    <p class="card-text text-muted mb-3">{{ $quiz->description }}</p>
                                    
                                    <div class="quiz-info mb-3">
                                        <p class="mb-2">
                                            <i class="fas fa-clock me-2"></i>{{ $quiz->duration }} minutes
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-question-circle me-2"></i>{{ $quiz->questions->count() }} questions
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-award me-2"></i>Passing Score: {{ $quiz->passing_score }}%
                                        </p>
                                    </div>
                                    
                                    @auth
                                        <a href="{{ route('quiz.show', ['id' => $quiz->id, 'details' => true]) }}" class="btn btn-primary w-100">
                                            <i class="fas fa-info-circle me-2"></i>View Details
                                        </a>
                                    @else
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login to Start
                                            </a>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @empty
        <div class="text-center py-5">
            <div class="empty-category">
                <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
                <h3>No Quizzes Available</h3>
                <p class="text-muted">Check back later for new quizzes!</p>
            </div>
        </div>
    @endforelse
</div>
@endsection