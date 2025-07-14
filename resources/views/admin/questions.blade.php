@extends('layouts.app')

@section('title', 'Manage Questions')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Manage Questions</h2>
    <a href="{{ route('admin.questions.create', ['quiz_id' => 1]) }}" class="btn btn-success mb-3">Add New Question</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Question</th>
                <th>Type</th>
                <th>Points</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>What is the capital of France?</td>
                <td>MCQ</td>
                <td>1</td>
                <td>
                    <a href="{{ route('admin.questions.edit', ['id' => 1]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('admin.questions.delete', ['id' => 1]) }}" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            <!-- More rows -->
        </tbody>
    </table>
</div>
@endsection
