@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Welcome, {{ Auth::user()->name ?? 'User' }}</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">My Quizzes</h5>
                    <p class="card-text">View and resume your quiz attempts.</p>
                    <a href="{{ route('user.quizzes') }}" class="btn btn-outline-primary">Go to My Quizzes</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Results</h5>
                    <p class="card-text">See your scores and performance analytics.</p>
                    <a href="{{ route('user.results') }}" class="btn btn-outline-success">View Results</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('quizzes.list') }}" class="btn btn-lg btn-primary">Browse Available Quizzes</a>
        </div>
    </div>
</div>
@endsection
