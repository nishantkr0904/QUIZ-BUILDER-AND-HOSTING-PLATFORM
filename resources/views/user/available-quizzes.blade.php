@extends('layouts.app')

@section('title', 'Available Quizzes')

@section('content')
<div class="container px-4 py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2 mb-0">Available Quizzes</h1>
            <p class="text-muted">Take a new quiz to test your knowledge</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($quizzes as $quiz)
            <div class="col-md-6 col-lg-4">
                <div class="card quiz-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                            <span class="badge bg-{{ $quiz->difficulty_color }} text-white">
                                {{ ucfirst($quiz->difficulty) }}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted mb-3">{{ Str::limit($quiz->description, 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                <i class="fas fa-clock me-1"></i> {{ $quiz->duration }} minutes
                                <span class="mx-2">•</span>
                                <i class="fas fa-question-circle me-1"></i> {{ $quiz->questions_count }} questions
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="{{ route('quiz.take', $quiz->id) }}" class="btn btn-primary w-100">
                            Start Quiz
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No available quizzes found. Check back later for new quizzes!
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
