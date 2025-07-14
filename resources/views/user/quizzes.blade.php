@extends('layouts.app')

@section('title', 'My Quizzes')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Quizzes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Date Attempted</th>
                <th>Score</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>General Knowledge</td>
                <td>2025-07-13</td>
                <td>8/10</td>
                <td><span class="badge bg-success">Completed</span></td>
                <td><a href="{{ route('quizzes.result', ['id' => 1]) }}" class="btn btn-sm btn-info">View Result</a></td>
            </tr>
            <tr>
                <td>Science Basics</td>
                <td>2025-07-10</td>
                <td>In Progress</td>
                <td><span class="badge bg-warning text-dark">In Progress</span></td>
                <td><a href="{{ route('quiz.take', ['id' => 2]) }}" class="btn btn-sm btn-primary">Resume</a></td>
            </tr>
            <!-- More rows -->
        </tbody>
    </table>
</div>
@endsection
