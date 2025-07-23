@extends('layouts.app')

@section('title', 'My Quizzes')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Quizzes</h2>

    @if($inProgressQuizzes->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0">In Progress</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Date Started</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inProgressQuizzes as $result)
                        <tr>
                            <td>{{ $result->quiz->title }}</td>
                            <td>{{ $result->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if($result->quiz->questions->count() > 0)
                                    {{ $result->answered_questions_count }}/{{ $result->quiz->questions->count() }} questions
                                @else
                                    No questions
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('quiz.resume', $result->quiz_id) }}" class="btn btn-primary btn-sm">Resume</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($completedQuizzes->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="h5 mb-0">Completed</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Date Completed</th>
                            <th>Score</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedQuizzes as $result)
                        <tr>
                            <td>{{ $result->quiz->title }}</td>
                            <td>{{ $result->completed_at->format('Y-m-d') }}</td>
                            <td>
                                @if($result->quiz->questions->count() > 0)
                                    {{ $result->score }}/{{ $result->quiz->questions->count() }}
                                    ({{ number_format(($result->score / $result->quiz->questions->count()) * 100, 1) }}%)
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('quizzes.result', $result->id) }}" class="btn btn-info btn-sm">View Result</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($completedQuizzes->count() === 0 && $inProgressQuizzes->count() === 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <p class="mb-3">You haven't attempted any quizzes yet.</p>
            <a href="{{ route('quizzes.list') }}" class="btn btn-primary">Browse Available Quizzes</a>
        </div>
    </div>
    @endif
</div>
@endsection
