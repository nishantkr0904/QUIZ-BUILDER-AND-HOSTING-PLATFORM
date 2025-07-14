@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 admin-dashboard">
                <div class="card-body">
                    <h5 class="card-title">Manage Quizzes</h5>
                    <p class="card-text">Create, edit, or delete quizzes.</p>
                    <a href="{{ route('admin.quizzes') }}" class="btn btn-outline-primary">Quiz Builder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 admin-dashboard">
                <div class="card-body">
                    <h5 class="card-title">Manage Questions</h5>
                    <p class="card-text">Add, edit, or remove questions from the question bank.</p>
                    <a href="{{ route('admin.questions') }}" class="btn btn-outline-warning">Manage Questions</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 admin-dashboard">
                <div class="card-body">
                    <h5 class="card-title">Analytics</h5>
                    <p class="card-text">View quiz statistics and user performance.</p>
                    <a href="{{ route('admin.analytics') }}" class="btn btn-outline-success">View Analytics</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
