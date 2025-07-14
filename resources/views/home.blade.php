@extends('layouts.app')

@section('title', 'Quiz Builder and Hosting Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="mb-4">Quiz Builder and Hosting Platform</h1>
            <p class="lead mb-5">Create, manage, and attempt quizzes with instant scoring. For admins, educators, and learners.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg">Sign Up</a>
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-success btn-lg">Register</a>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('leaderboard') }}" class="btn btn-outline-info">Show Leaderboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
