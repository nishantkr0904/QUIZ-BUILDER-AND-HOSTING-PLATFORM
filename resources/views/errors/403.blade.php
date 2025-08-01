@extends('layouts.app')

@section('title', '403 Forbidden - Quiz Builder and Hosting Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page mb-4">
                <i class="fas fa-exclamation-circle text-danger fa-5x mb-3"></i>
                <h1 class="error-code">403</h1>
                <h2 class="error-title mb-3">Access Denied</h2>
                <p class="error-message text-muted mb-4">
                    {{ $exception->getMessage() ?: 'You do not have permission to access this area.' }}
                </p>
                <div class="d-grid gap-2">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Go to Homepage
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .error-page {
        padding: 40px 0;
    }
    .error-code {
        font-size: 72px;
        font-weight: 700;
        color: #dc3545;
        margin: 20px 0;
    }
    .error-title {
        font-size: 24px;
        font-weight: 500;
        color: #343a40;
    }
</style>
@endpush
@endsection
