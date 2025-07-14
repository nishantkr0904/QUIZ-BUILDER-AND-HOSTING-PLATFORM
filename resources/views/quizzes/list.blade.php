@extends('layouts.app')

@section('title', 'Quiz Listing')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Available Quizzes</h2>
    <div class="row">
        {{-- Example quiz cards, replace with dynamic content --}}
        <div class="col-md-4 mb-4" v-for="quiz in quizzes" :key="quiz.id">
            <div class="card quiz-card">
                <div class="card-body">
                    <h5 class="card-title">Quiz Title</h5>
                    <p class="card-text">Category: <span class="badge bg-info">Science</span></p>
                    <p class="card-text">Difficulty: <span class="badge bg-warning text-dark">Medium</span></p>
                    <a href="{{ route('quiz.take', ['id' => 1]) }}" class="btn btn-primary">Start Quiz</a>
                </div>
            </div>
        </div>
        {{-- End example --}}
    </div>
</div>
@endsection
