@extends('layouts.app')

@section('title', 'Manage Questions - ' . $quiz->title)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Manage Questions</h1>
            <h2 class="h5 text-muted mb-0">{{ $quiz->title }}</h2>
        </div>
        <div>
            <a href="{{ route('admin.questions.create', $quiz->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Question
            </a>
            <a href="{{ route('admin.quizzes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Questions ({{ $quiz->questions->count() }})</span>
                @if($quiz->questions->count() > 0)
                    <button class="btn btn-sm btn-secondary" onclick="toggleReorder()">
                        <i class="fas fa-sort"></i> Reorder Questions
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($quiz->questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="questionsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Question</th>
                                <th style="width: 120px">Type</th>
                                <th style="width: 100px">Points</th>
                                <th style="width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="questionsList">
                            @foreach($quiz->questions as $question)
                                <tr data-id="{{ $question->id }}">
                                    <td class="align-middle">
                                        <span class="question-number">{{ $loop->iteration }}</span>
                                        <div class="drag-handle d-none">
                                            <i class="fas fa-grip-vertical"></i>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        {{ Str::limit($question->question_text, 100) }}
                                        @if(strlen($question->question_text) > 100)
                                            <a href="#" onclick="showFullQuestion('{{ htmlspecialchars($question->question_text) }}')">[more]</a>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-info">
                                            {{ Str::title(str_replace('_', ' ', $question->question_type)) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">{{ $question->points }}</td>
                                    <td class="align-middle">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.questions.edit', $question->id) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="Edit Question">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-info"
                                                    title="Preview Question"
                                                    onclick="previewQuestion({{ $question->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete Question"
                                                    onclick="confirmDelete({{ $question->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $question->id }}"
                                              action="{{ route('admin.questions.destroy', $question->id) }}"
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
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No questions added yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Full Question Modal -->
<div class="modal fade" id="questionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Question Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="fullQuestionText"></p>
            </div>
        </div>
    </div>
</div>

<!-- Question Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Question Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Question preview content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
let sortable = null;

function toggleReorder() {
    const dragHandles = document.querySelectorAll('.drag-handle');
    const questionNumbers = document.querySelectorAll('.question-number');
    
    dragHandles.forEach(handle => handle.classList.toggle('d-none'));
    questionNumbers.forEach(number => number.classList.toggle('d-none'));
    
    if (sortable === null) {
        // Initialize sorting
        sortable = new Sortable(document.getElementById('questionsList'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function() {
                // Get new order
                const rows = document.querySelectorAll('#questionsList tr');
                const newOrder = Array.from(rows).map(row => row.dataset.id);
                
                // Save new order
                fetch('{{ route("admin.questions.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ questions: newOrder })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update question numbers
                        rows.forEach((row, index) => {
                            row.querySelector('.question-number').textContent = index + 1;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    } else {
        sortable.destroy();
        sortable = null;
    }
}

function showFullQuestion(text) {
    document.getElementById('fullQuestionText').textContent = text;
    new bootstrap.Modal(document.getElementById('questionModal')).show();
}

function previewQuestion(questionId) {
    // Load question preview content
    fetch(`/admin/questions/${questionId}/preview`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('previewContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        })
        .catch(error => console.error('Error:', error));
}

function confirmDelete(questionId) {
    if (confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
        document.getElementById(`delete-form-${questionId}`).submit();
    }
}
</script>
@endpush
@endsection
