@extends('layouts.app')

@section('title', 'Quiz Builder (Admin)')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Quiz Builder</h2>
    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-success mb-3">Create New Quiz</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Difficulty</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>General Knowledge</td>
                <td>General</td>
                <td>Easy</td>
                <td>10 min</td>
                <td>
                    <a href="{{ route('admin.quizzes.edit', ['id' => 1]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('admin.quizzes.delete', ['id' => 1]) }}" class="btn btn-sm btn-danger">Delete</a>
                    <a href="{{ route('admin.questions', ['quiz_id' => 1]) }}" class="btn btn-sm btn-info">Questions</a>
                </td>
            </tr>
            <!-- More rows -->
        </tbody>
    </table>
</div>
@endsection
