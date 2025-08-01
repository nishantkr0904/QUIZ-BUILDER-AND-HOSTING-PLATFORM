@extends('layouts.app')

@section('title', $quiz->title . ' - Quiz Builder and Hosting Platform')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}#{{ Str::slug($quiz->category->name) }}">{{ $quiz->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $quiz->title }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h1 class="h2 mb-0">{{ $quiz->title }}</h1>
                        <span class="badge bg-{{ $quiz->difficulty == 'easy' ? 'success' : ($quiz->difficulty == 'medium' ? 'warning' : 'danger') }}">
                            {{ ucfirst($quiz->difficulty) }}
                        </span>
                    </div>

                    <div class="quiz-description mb-4">
                        <h5 class="text-muted mb-2">Description</h5>
                        <p>{{ $quiz->description }}</p>
                    </div>

                    <div class="quiz-details mb-4">
                        <h5 class="text-muted mb-3">Quiz Details</h5>
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Duration</small>
                                        <strong>{{ $quiz->duration }} minutes</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-question-circle text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Questions</small>
                                        <strong>{{ $quiz->questions->count() }} total</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-award text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Passing Score</small>
                                        <strong>{{ $quiz->passing_score }}%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quiz-rules mb-4">
                        <h5 class="text-muted mb-3">Quiz Rules</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Time limit of {{ $quiz->duration }} minutes
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Must score {{ $quiz->passing_score }}% or higher to pass
                            </li>
                            @if($quiz->review_enabled)
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Review of answers available after completion
                            </li>
                            @endif
                            <li>
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Quiz auto-submits when time expires
                            </li>
                        </ul>
                    </div>

                    <div class="text-center">
                        <form action="{{ route('quiz.start', $quiz->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-play-circle me-2"></i>Start Quiz
                            </button>
                        </form>
                        <small class="text-muted mt-2 d-block">
                            Click 'Start Quiz' when you're ready to begin
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
