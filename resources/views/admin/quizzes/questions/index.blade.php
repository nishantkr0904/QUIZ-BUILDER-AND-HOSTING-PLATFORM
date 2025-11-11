@extends('layouts.app')

@section('title', 'Manage Questions - ' . $quiz->title)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Manage Questions</h1>
            <p class="text-muted">Quiz: {{ $quiz->title }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.quizzes.questions.create', $quiz->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Question
            </a>
            <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-secondary">
                <i class="fas fa-chevron-left"></i> Back to Quiz
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($questions->count() > 0)
                <form id="questions-form" method="POST" action="{{ route('admin.quizzes.questions.reorder', $quiz->id) }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="40%">Question</th>
                                    <th width="15%">Type</th>
                                    <th width="10%">Points</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-questions">
                                @foreach($questions as $question)
                                    <tr data-id="{{ $question->id }}">
                                        <td>
                                            <i class="fas fa-grip-vertical handle cursor-move"></i>
                                            <input type="hidden" 
                                                   name="questions[{{ $loop->index }}][id]" 
                                                   value="{{ $question->id }}">
                                            <input type="hidden" 
                                                   name="questions[{{ $loop->index }}][order]" 
                                                   value="{{ $loop->iteration }}">
                                        </td>
                                        <td>{{ Str::limit($question->question, 100) }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ str_replace('_', ' ', ucfirst($question->type)) }}
                                            </span>
                                        </td>
                                        <td>{{ $question->points }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.quizzes.questions.edit', [$quiz->id, $question->id]) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit Question">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete({{ $quiz->id }}, {{ $question->id }})"
                                                        title="Delete Question">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $question->id }}" 
                                                  action="{{ route('admin.quizzes.questions.destroy', [$quiz->id, $question->id]) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <h4>No questions added yet</h4>
                    <p class="text-muted">Click the "Add Question" button to start creating questions.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable
    if (document.getElementById('sortable-questions')) {
        new Sortable(document.getElementById('sortable-questions'), {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                // Update order inputs
                document.querySelectorAll('#sortable-questions tr').forEach((row, index) => {
                    row.querySelector('input[name*="[order]"]').value = index + 1;
                });
                // Submit the form
                document.getElementById('questions-form').submit();
            }
        });
    }
});

function confirmDelete(quizId, questionId) {
    if (confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
        document.getElementById('delete-form-' + questionId).submit();
    }
}
</script>
@endpush

@push('styles')
<style>
.cursor-move {
    cursor: move;
}
.handle {
    color: #6c757d;
}
</style>
@endpush
