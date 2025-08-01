@extends('layouts.app')

@section('title', 'Welcome - Quiz Builder and Hosting Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 text-center">
            <h1 class="display-4 mb-4">Quiz Builder and Hosting Platform</h1>
            <p class="lead mb-5">Test your knowledge with interactive quizzes. Choose your role to get started.</p>
            
            <div class="row g-4 justify-content-center">
                <!-- User Login/Register Card -->
                <div class="col-md-5">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <i class="fas fa-user-circle fa-3x mb-3 text-primary"></i>
                            <h3 class="card-title mb-3">User Access</h3>
                            <p class="card-text text-muted mb-4">Take quizzes and track your progress</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>User Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus me-2"></i>New User? Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Login Card -->
                <div class="col-md-5">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <i class="fas fa-user-shield fa-3x mb-3 text-danger"></i>
                            <h3 class="card-title mb-3">Admin Access</h3>
                            <p class="card-text text-muted mb-4">Create and manage quizzes</p>
                            <div class="d-grid">
                                <a href="{{ route('admin.login') }}" class="btn btn-danger">
                                    <i class="fas fa-lock me-2"></i>Admin Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-5 pt-4">
                <h2 class="h3 mb-4">Platform Features</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card p-4">
                            <i class="fas fa-tasks fa-2x mb-3 text-primary"></i>
                            <h4 class="h5">Multiple Quiz Types</h4>
                            <p class="text-muted">Various categories and difficulty levels</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card p-4">
                            <i class="fas fa-clock fa-2x mb-3 text-success"></i>
                            <h4 class="h5">Timed Quizzes</h4>
                            <p class="text-muted">Challenge yourself with time limits</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card p-4">
                            <i class="fas fa-chart-line fa-2x mb-3 text-info"></i>
                            <h4 class="h5">Progress Tracking</h4>
                            <p class="text-muted">Monitor your improvement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
        border: none;
        border-radius: 10px;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .feature-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .feature-card:hover {
        background-color: #fff;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
    }
    .lead {
        font-size: 1.15rem;
        color: #6c757d;
    }
</style>
@endpush
@endsection
