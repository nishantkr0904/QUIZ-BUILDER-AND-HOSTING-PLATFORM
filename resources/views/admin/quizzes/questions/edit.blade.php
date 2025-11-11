@extends('layouts.app')

@section('title', 'Edit Question')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h4 mb-0">Edit Question</h1>
                    <a href="{{ route('admin.quizzes.questions.index', $quiz->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-chevron-left"></i> Back to Questions
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quizzes.questions.update', [$quiz->id, $question->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}"

                        <div class="mb-3">
                            <label for="question" class="form-label">Question Text</label>
                            <textarea class="form-control @error('question') is-invalid @enderror" 
                                      id="question" 
                                      name="question" 
                                      rows="3" 
                                      required>{{ old('question', $question->question) }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Question Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required
                                    onchange="toggleOptionsDisplay(this.value)">
                                <option value="">Select a type</option>
                                @foreach($questionTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type', $question->type) == $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="options-container" class="mb-3" style="display: none;">
                            <label class="form-label">Answer Options</label>
                            <div id="options-list">
                                @if($question->type !== 'true_false')
                                    @foreach(old('options', $question->options ?? []) as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text" 
                                                   class="form-control @error('options.' . $index) is-invalid @enderror"
                                                   name="options[]" 
                                                   value="{{ $option }}" 
                                                   placeholder="Option {{ $index + 1 }}">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @error('options.' . $index)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addOption()">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <div id="true-false-container" class="mb-3" style="display: none;">
                            <label class="form-label">Correct Answer</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="correct_answer" 
                                           id="true" 
                                           value="true"
                                           @checked(old('correct_answer', $question->correct_answer) === 'true')>
                                    <label class="form-check-label" for="true">True</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="correct_answer" 
                                           id="false" 
                                           value="false"
                                           @checked(old('correct_answer', $question->correct_answer) === 'false')>
                                    <label class="form-check-label" for="false">False</label>
                                </div>
                            </div>
                            @error('correct_answer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="correct-answer-container" class="mb-3" style="display: none;">
                            <label for="correct_answer" class="form-label">Correct Answer</label>
                            <input type="text" 
                                   class="form-control @error('correct_answer') is-invalid @enderror" 
                                   id="correct_answer" 
                                   name="correct_answer" 
                                   value="{{ old('correct_answer', $question->correct_answer) }}">
                            @error('correct_answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Must match one of the options exactly.</div>
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="number" 
                                   class="form-control @error('points') is-invalid @enderror" 
                                   id="points" 
                                   name="points" 
                                   value="{{ old('points', $question->points) }}" 
                                   min="1" 
                                   required>
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="explanation" class="form-label">Explanation (Optional)</label>
                            <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                      id="explanation" 
                                      name="explanation" 
                                      rows="3">{{ old('explanation', $question->explanation) }}</textarea>
                            @error('explanation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Shown to users after answering the question.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.quizzes.questions.index', $quiz->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    toggleOptionsDisplay(document.getElementById('type').value);
});

function toggleOptionsDisplay(type) {
    const optionsContainer = document.getElementById('options-container');
    const trueFalseContainer = document.getElementById('true-false-container');
    const correctAnswerContainer = document.getElementById('correct-answer-container');

    if (type === 'true_false') {
        optionsContainer.style.display = 'none';
        trueFalseContainer.style.display = 'block';
        correctAnswerContainer.style.display = 'none';
    } else if (type === 'multiple_choice' || type === 'single_choice') {
        optionsContainer.style.display = 'block';
        trueFalseContainer.style.display = 'none';
        correctAnswerContainer.style.display = 'block';
    } else {
        optionsContainer.style.display = 'none';
        trueFalseContainer.style.display = 'none';
        correctAnswerContainer.style.display = 'none';
    }
}

function addOption() {
    const optionsList = document.getElementById('options-list');
    const count = optionsList.children.length;
    
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" 
               class="form-control"
               name="options[]" 
               placeholder="Option ${count + 1}">
        <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    optionsList.appendChild(div);
}

function removeOption(button) {
    button.closest('.input-group').remove();
    
    // Renumber the remaining options
    const options = document.querySelectorAll('#options-list input[type="text"]');
    options.forEach((input, index) => {
        input.placeholder = `Option ${index + 1}`;
    });
}
</script>
@endpush
