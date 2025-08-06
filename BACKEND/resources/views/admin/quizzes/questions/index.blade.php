@extends('layouts.app')

@section('title', 'Manage Questions - ' . $quiz->title)

@push('styles')
<style>
    .question-list {
        list-style: none;
        padding: 0;
    }
    .question-item {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
        background: #fff;
    }
    .question-header {
        padding: 1rem;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .question-body {
        padding: 1rem;
    }
    .question-actions {
        display: flex;
        gap: 0.5rem;
    }
    .type-badge {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        background: #e9ecef;
    }
    .options-list {
        list-style: none;
        padding-left: 0;
    }
    .option-item {
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
    }
    .option-item.correct {
        border-color: #198754;
        background-color: #d1e7dd;
    }
    .question-drag-handle {
        cursor: move;
        color: #6c757d;
        margin-right: 1rem;
    }
    .sortable-ghost {
        opacity: 0.5;
        background: #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Questions - {{ $quiz->title }}</h1>
        <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Question
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($questions->isEmpty())
        <div class="alert alert-info">
            No questions added yet. Click the "Add Question" button to create your first question.
        </div>
    @else
        <ul class="question-list" id="sortableQuestions">
            @foreach($questions as $question)
            <li class="question-item" data-question-id="{{ $question->id }}">
                <div class="question-header">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-grip-vertical question-drag-handle"></i>
                        <span class="h5 mb-0">Question {{ $loop->iteration }}</span>
                        <span class="type-badge ms-2">{{ ucwords(str_replace('_', ' ', $question->type)) }}</span>
                    </div>
                    <div class="question-actions">
                        <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this question?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="question-body">
                    <p class="mb-3">{{ $question->question }}</p>
                    
                    @if($question->type === 'true_false')
                        <div class="mb-3">
                            <strong>Correct Answer:</strong> {{ ucfirst($question->correct_answer) }}
                        </div>
                    @else
                        <div class="mb-3">
                            <strong>Options:</strong>
                            <ul class="options-list mt-2">
                                @foreach($question->options as $option)
                                    <li class="option-item {{ $option === $question->correct_answer ? 'correct' : '' }}">
                                        @if($option === $question->correct_answer)
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                        @else
                                            <i class="fas fa-circle text-muted me-2"></i>
                                        @endif
                                        {{ $option }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($question->explanation)
                        <div class="mt-3">
                            <strong>Explanation:</strong>
                            <p class="text-muted mb-0">{{ $question->explanation }}</p>
                        </div>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortable = new Sortable(document.getElementById('sortableQuestions'), {
        animation: 150,
        handle: '.question-drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd: function() {
            const questions = Array.from(document.querySelectorAll('.question-item'))
                .map(item => item.dataset.questionId);
            
            fetch('{{ route('admin.quizzes.questions.reorder', $quiz) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ questions })
            }).then(response => response.json())
              .then(data => {
                  if (data.message) {
                      // Optional: Show success message
                  }
              })
              .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endpush
