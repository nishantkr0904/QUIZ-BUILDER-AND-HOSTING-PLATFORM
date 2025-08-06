@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3">Admin Dashboard</h1>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Stats Cards -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Quizzes</h5>
                    <h2 class="mb-0">{{ $stats['total_quizzes'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <h2 class="mb-0">{{ $stats['total_categories'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Quiz Attempts</h5>
                    <h2 class="mb-0">{{ $stats['quiz_attempts'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Quizzes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Quizzes</h5>
                </div>
                <div class="card-body">
                    @if(count($stats['recent_quizzes']) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($stats['recent_quizzes'] as $quiz)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $quiz->title }}</h6>
                                        <small class="text-muted">{{ $quiz->category->name }}</small>
                                    </div>
                                    <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No quizzes created yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quizzes by Category -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quizzes by Category</h5>
                </div>
                <div class="card-body">
                    @if(count($stats['quiz_by_category']) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($stats['quiz_by_category'] as $category)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $category->name }}
                                    <span class="badge bg-primary rounded-pill">{{ $category->quizzes_count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No categories created yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Quiz
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                            <i class="fas fa-folder-plus"></i> Add Category
                        </a>
                        <a href="{{ route('admin.users') }}" class="btn btn-info">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
