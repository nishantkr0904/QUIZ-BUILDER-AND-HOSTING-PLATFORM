@extends('layouts.app')

@section('title', isset($question) ? 'Edit Question' : 'Add Question')

@push('styles')
<style>
    .option-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    .option-input {
        flex: 1;
    }
    .remove-option {
        color: #dc3545;
        cursor: pointer;
    }
    .correct-answer-radio {
        width: 1.5rem;
        height: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">
                        {{ isset($question) ? 'Edit Question' : 'Add Question' }}
                        - {{ $quiz->title }}
                    </h1>
                </div>

                <div class="card-body">
                    <form action="{{ isset($question) 
                        ? route('admin.quizzes.questions.update', [$quiz, $question]) 
                        : route('admin.quizzes.questions.store', $quiz) }}" 
                          method="POST" 
                          id="questionForm">
                        @csrf
                        @if(isset($question))
                            @method('PUT')
                        @endif

                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <input type="hidden" name="order" value="{{ $nextOrder ?? $question->order ?? 0 }}">

                        <div class="mb-3">
                            <label for="question" class="form-label">Question Text</label>
                            <textarea name="question" 
                                      id="question" 
                                      class="form-control @error('question') is-invalid @enderror" 
                                      rows="3" 
                                      required>{{ old('question', $question->question ?? '') }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Question Type</label>
                            <select name="type" 
                                    id="type" 
                                    class="form-select @error('type') is-invalid @enderror" 
                                    required>
                                <option value="">Select question type</option>
                                @foreach($questionTypes as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ old('type', $question->type ?? '') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Multiple Choice / Single Choice Options -->
                        <div id="optionsContainer" class="mb-3 d-none">
                            <label class="form-label">Options</label>
                            <div id="optionsList">
                                <!-- Options will be dynamically added here -->
                            </div>
                            <button type="button" id="addOption" class="btn btn-outline-secondary mt-2">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                            <div class="text-muted small mt-2">
                                * Select the radio button next to the correct answer
                            </div>
                            @error('options')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('correct_answer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- True/False Options -->
                        <div id="trueFalseContainer" class="mb-3 d-none">
                            <label class="form-label">Correct Answer</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" 
                                       class="btn-check" 
                                       name="correct_answer" 
                                       id="true" 
                                       value="true" 
                                       {{ old('correct_answer', $question->correct_answer ?? '') === 'true' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="true">True</label>

                                <input type="radio" 
                                       class="btn-check" 
                                       name="correct_answer" 
                                       id="false" 
                                       value="false" 
                                       {{ old('correct_answer', $question->correct_answer ?? '') === 'false' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="false">False</label>
                            </div>
                        </div>>
                            <label class="form-label">Options</label>
                            <div id="optionsList">
                                @if(isset($question) && $question->type !== 'true_false')
                                    @foreach($question->options as $index => $option)
                                        <div class="option-row">
                                            <input type="radio" 
                                                   name="correct_answer" 
                                                   value="{{ $option }}" 
                                                   class="correct-answer-radio"
                                                   {{ $question->correct_answer === $option ? 'checked' : '' }}>
                                            <input type="text" 
                                                   name="options[]" 
                                                   class="form-control option-input" 
                                                   value="{{ $option }}" 
                                                   required>
                                            <i class="fas fa-times remove-option"></i>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="option-row">
                                        <input type="radio" name="correct_answer" class="correct-answer-radio" required>
                                        <input type="text" name="options[]" class="form-control option-input" required>
                                        <i class="fas fa-times remove-option"></i>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="addOption" class="btn btn-outline-secondary mt-2">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                            @error('options')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('correct_answer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="trueFalseContainer" class="mb-3 {{ old('type', $question->type ?? '') !== 'true_false' ? 'd-none' : '' }}">
                            <label class="form-label">Correct Answer</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" 
                                       class="btn-check" 
                                       name="correct_answer" 
                                       id="true" 
                                       value="true" 
                                       {{ old('correct_answer', $question->correct_answer ?? '') === 'true' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="true">True</label>

                                <input type="radio" 
                                       class="btn-check" 
                                       name="correct_answer" 
                                       id="false" 
                                       value="false" 
                                       {{ old('correct_answer', $question->correct_answer ?? '') === 'false' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="false">False</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="number" 
                                   name="points" 
                                   id="points" 
                                   class="form-control @error('points') is-invalid @enderror" 
                                   value="{{ old('points', $question->points ?? 1) }}" 
                                   min="1" 
                                   max="100" 
                                   required>
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="explanation" class="form-label">Explanation (Optional)</label>
                            <textarea name="explanation" 
                                      id="explanation" 
                                      class="form-control @error('explanation') is-invalid @enderror" 
                                      rows="2">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                            @error('explanation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Questions
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($question) ? 'Update' : 'Save' }} Question
                            </button>
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
    const form = document.getElementById('questionForm');
    const type = document.getElementById('type');
    const optionsContainer = document.getElementById('optionsContainer');
    const trueFalseContainer = document.getElementById('trueFalseContainer');
    const optionsList = document.getElementById('optionsList');
    const addOptionBtn = document.getElementById('addOption');

    // Function to create an option row
    function createOptionRow(value = '', isCorrect = false) {
        const optionRow = document.createElement('div');
        optionRow.className = 'option-row';
        optionRow.innerHTML = `
            <input type="radio" name="correct_answer" class="correct-answer-radio" ${isCorrect ? 'checked' : ''} required>
            <input type="text" name="options[]" class="form-control option-input" value="${value}" required placeholder="Enter option text">
            <i class="fas fa-times remove-option"></i>
        `;
        return optionRow;
    }

    // Function to initialize options for multiple/single choice
    function initializeOptions() {
        optionsList.innerHTML = ''; // Clear existing options
        
        // If editing and has existing options
        @if(isset($question) && $question->options)
            @foreach($question->options as $option)
                optionsList.appendChild(createOptionRow(
                    '{{ $option }}', 
                    '{{ $question->correct_answer }}' === '{{ $option }}'
                ));
            @endforeach
        @else
            // Add two default empty options for new questions
            optionsList.appendChild(createOptionRow());
            optionsList.appendChild(createOptionRow());
        @endif
    }

    // Handle question type change
    type.addEventListener('change', function() {
        optionsContainer.classList.add('d-none');
        trueFalseContainer.classList.add('d-none');

        switch(this.value) {
            case 'multiple_choice':
            case 'single_choice':
                optionsContainer.classList.remove('d-none');
                initializeOptions();
                break;
            case 'true_false':
                trueFalseContainer.classList.remove('d-none');
                break;
        }
    });

    // Initialize based on selected type
    if (type.value) {
        type.dispatchEvent(new Event('change'));
    }

    // Add new option
    addOptionBtn.addEventListener('click', function() {
        if (optionsList.children.length >= 6) {
            alert('Maximum 6 options allowed');
            return;
        }
        
        optionsList.appendChild(createOptionRow());
        optionsList.lastElementChild.querySelector('input[type="text"]').focus();
    });

    // Remove option
    optionsList.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            if (optionsList.children.length <= 2) {
                alert('Minimum 2 options required');
                return;
            }
            e.target.closest('.option-row').remove();
        }
    });

    // Update radio values when option text changes
    optionsList.addEventListener('input', function(e) {
        if (e.target.matches('input[type="text"]')) {
            const row = e.target.closest('.option-row');
            const radio = row.querySelector('input[type="radio"]');
            radio.value = e.target.value;
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!type.value) {
            alert('Please select a question type');
            e.preventDefault();
            return;
        }

        if (type.value !== 'true_false') {
            const options = document.querySelectorAll('input[name="options[]"]');
            const correctAnswer = document.querySelector('input[name="correct_answer"]:checked');
            
            if (options.length < 2) {
                alert('Please add at least 2 options');
                e.preventDefault();
                return;
            }
            
            if (!correctAnswer) {
                alert('Please select the correct answer');
                e.preventDefault();
                return;
            }

            // Check for duplicate options
            const optionValues = Array.from(options).map(opt => opt.value);
            if (new Set(optionValues).size !== optionValues.length) {
                alert('Options must be unique');
                e.preventDefault();
                return;
            }
        } else {
            const trueFalseAnswer = document.querySelector('input[name="correct_answer"]:checked');
            if (!trueFalseAnswer) {
                alert('Please select true or false');
                e.preventDefault();
                return;
            }
        }
    });
});
</script>
@endpush
