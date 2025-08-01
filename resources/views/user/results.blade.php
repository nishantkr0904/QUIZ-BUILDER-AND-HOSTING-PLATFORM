@extends('layouts.app')

@section('title', 'My Results')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Quiz Results</h2>
    <div class="card">
        <div class="card-body">
            @if($results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Quiz</th>
                                <th>Score</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                            <tr>
                                <td>{{ $result->quiz->title }}</td>
                                <td>{{ $result->score }}</td>
                                <td>{{ $result->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($result->completed)
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-warning">In Progress</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="mb-0">You haven't attempted any quizzes yet.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Browse Available Quizzes</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
