@extends('layouts.app')

@section('title', 'Admin Login - Quiz Builder and Hosting Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-shield fa-3x text-danger mb-3"></i>
                        <h2 class="card-title mb-3">Admin Login</h2>
                        <p class="text-muted">Access the admin dashboard to manage quizzes</p>
                    </div>

                    <form method="POST" action="{{ route('admin.login') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-in-alt me-2"></i>Login as Admin
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #b02a37;
    }
</style>
@endpush
@endsection
