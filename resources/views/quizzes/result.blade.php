@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Quiz Results</h2>
    <div class="alert alert-success">
        <strong>Congratulations!</strong> You scored <span class="fw-bold">8/10</span>.
    </div>
    <div class="mb-4">
        <h5>Review Answers</h5>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Q1: What is the capital of France?
                <span class="badge bg-success">Correct</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Q2: 2 + 2 = ?
                <span class="badge bg-danger">Incorrect</span>
            </li>
            <!-- More questions -->
        </ul>
    </div>
    <a href="{{ route('quizzes.list') }}" class="btn btn-primary">Back to Quizzes</a>
</div>
@endsection
