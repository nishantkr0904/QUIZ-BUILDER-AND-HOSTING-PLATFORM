@extends('layouts.app')

@section('title', 'User Dashboard')

@section('styles')
<style>
.stat-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    pointer-events: none;
}

.quiz-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.quiz-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-color: #3b82f6;
}

.progress-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.1rem;
    background: #f8f9fa;
    border: 3px solid;
}

.progress-circle.high {
    color: #198754;
    border-color: #198754;
}

.progress-circle.medium {
    color: #fd7e14;
    border-color: #fd7e14;
}

.progress-circle.low {
    color: #dc3545;
    border-color: #dc3545;
}

.history-item {
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
    padding: 1rem;
}

.history-item:hover {
    background-color: #f8f9fa;
    border-left-color: #3b82f6;
}

.achievement-badge {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 2rem;
    margin: 0 auto;
    margin-bottom: 1rem;
}

.chart-container {
    position: relative;
    min-height: 300px;
}
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">My Dashboard</h1>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-play-circle me-2"></i>Take New Quiz
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-0">Quizzes Taken</p>
                            <h4 class="mb-0">{{ $stats['quizzes_taken'] ?? 0 }}</h4>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-0">Average Score</p>
                            <h4 class="mb-0">{{ $stats['average_score'] ?? '0%' }}</h4>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-0">Best Score</p>
                            <h4 class="mb-0">{{ $stats['best_score'] ?? '0%' }}</h4>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-0">Time Spent</p>
                            <h4 class="mb-0">{{ $stats['total_time'] ?? '0h' }}</h4>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Attempts -->
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Quiz Attempts</h5>
                    <a href="{{ route('user.results') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentAttempts->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fs-1 text-muted mb-3"></i>
                            <p class="mb-0">No quiz attempts yet.</p>
                            <small class="text-muted">Start your first quiz to see your results here!</small>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentAttempts as $attempt)
                                <div class="history-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $attempt['quiz_name'] }}</h6>
                                        <small class="text-muted">{{ $attempt['attempted_at'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="progress-circle {{ $attempt['passed'] ? 'high' : 'low' }}">
                                            {{ $attempt['score'] }}
                                        </div>
                                        <small class="d-block mt-1 {{ $attempt['passed'] ? 'text-success' : 'text-danger' }}">
                                            {{ $attempt['passed'] ? 'Passed' : 'Failed' }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Available Quizzes -->
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Available Quizzes</h5>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($availableQuizzes->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fs-1 text-success mb-3"></i>
                            <p class="mb-0">All Done!</p>
                            <small class="text-muted">You've completed all available quizzes.</small>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($availableQuizzes as $quiz)
                                <div class="quiz-card p-3 mb-3">
                                    <h6 class="mb-1">{{ $quiz['title'] }}</h6>
                                    <p class="small text-muted mb-2">{{ Str::limit($quiz['description'], 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small">
                                            <span class="me-2">
                                                <i class="fas fa-question-circle"></i>
                                                {{ $quiz['question_count'] }} questions
                                            </span>
                                            <span class="me-2">
                                                <i class="fas fa-clock"></i>
                                                {{ $quiz['duration_minutes'] }} min
                                            </span>
                                        </div>
                                        <span class="badge bg-{{ $quiz['difficulty'] === 'easy' ? 'success' : ($quiz['difficulty'] === 'medium' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($quiz['difficulty']) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
