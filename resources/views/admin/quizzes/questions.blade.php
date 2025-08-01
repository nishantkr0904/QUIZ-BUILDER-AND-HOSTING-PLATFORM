@extends('layouts.admin')

@section('title', 'Manage Questions')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet" />
<style>
.question-card {
    cursor: move;
}
.question-card.dragging {
    opacity: 0.5;
}
.question-placeholder {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    margin: 1rem 0;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Questions for: {{ $quiz->title }}</h1>
            <p class="text-muted">
                {{ $quiz->questions->count() }} questions | 
                {{ ucfirst($quiz->difficulty) }} difficulty
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
            <a href="{{ route('admin.quizzes.questions.create', $quiz->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Question
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="questions-container" id="questionsList">
        @forelse($quiz->questions as $question)
            <div class="card shadow-sm mb-4 question-card" data-id="{{ $question->id }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-secondary me-2">Q{{ $loop->iteration }}</span>
                            <span class="badge bg-{{ $question->question_type === 'multiple_choice' ? 'info' : 'warning' }}">
                                {{ $question->question_type === 'multiple_choice' ? 'Multiple Choice' : 'True/False' }}
                            </span>
                            <span class="badge bg-success ms-2">{{ $question->points }} pts</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.quizzes.questions.edit', [$quiz->id, $question->id]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.quizzes.questions.destroy', [$quiz->id, $question->id]) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <h5 class="card-title mb-4">{{ $question->question_text }}</h5>

                    <div class="options-list">
                        @if($question->question_type === 'multiple_choice')
                            @foreach($question->options as $key => $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           disabled 
                                           {{ $key === $question->correct_answer ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $key === $question->correct_answer ? 'text-success fw-bold' : '' }}">
                                        {{ chr(65 + $key) }}) {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="radio" 
                                       disabled 
                                       {{ $question->correct_answer === 'true' ? 'checked' : '' }}>
                                <label class="form-check-label {{ $question->correct_answer === 'true' ? 'text-success fw-bold' : '' }}">
                                    True
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       disabled 
                                       {{ $question->correct_answer === 'false' ? 'checked' : '' }}>
                                <label class="form-check-label {{ $question->correct_answer === 'false' ? 'text-success fw-bold' : '' }}">
                                    False
                                </label>
                            </div>
                        @endif
                    </div>

                    @if($question->explanation)
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Explanation:
                            </small>
                            <p class="mb-0 small">{{ $question->explanation }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-questions fa-3x mb-3"></i>
                    <h5>No questions yet</h5>
                    <p>Start by adding your first question to this quiz.</p>
                    <a href="{{ route('admin.quizzes.questions.create', $quiz->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Question
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionsList = document.getElementById('questionsList');
    
    if (questionsList) {
        new Sortable(questionsList, {
            animation: 150,
            handle: '.question-card',
            dragClass: 'dragging',
            ghostClass: 'question-placeholder',
            onEnd: function() {
                const questions = Array.from(questionsList.children).map((item, index) => {
                    return item.dataset.id;
                });
                
                fetch('{{ route("admin.quizzes.questions.reorder", $quiz->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ questions: questions })
                });
            }
        });
    }
});
</script>
@endpush
