@extends('layouts.app')

@section('title', 'Quiz Listing')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Available Quizzes</h2>
    <div class="row">
        @forelse($quizzes as $quiz)
        <div class="col-md-4 mb-4">
            <div class="card quiz-card">
                <div class="card-body">
                    <h5 class="card-title">{{ $quiz->title }}</h5>
                    <p class="card-text">Category: <span class="badge bg-info">{{ $quiz->category->name }}</span></p>
                    <p class="card-text">Difficulty: <span class="badge bg-warning text-dark">{{ ucfirst($quiz->difficulty) }}</span></p>
                    <p class="card-text">Duration: {{ $quiz->duration }} minutes</p>
                    <p class="card-text">Passing Score: {{ $quiz->passing_score }}%</p>
                    <a href="{{ route('quiz.take', ['id' => $quiz->id]) }}" class="btn btn-primary">Start Quiz</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                No quizzes available at the moment.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
