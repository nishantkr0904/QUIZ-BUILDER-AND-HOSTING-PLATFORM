@extends('layouts.app')

@section('title', 'Add Question')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">Add Question to {{ $quiz->title }}</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.questions.store', $quiz->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text</label>
                            <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                      id="question_text" 
                                      name="question_text" 
                                      rows="3" 
                                      required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="question_type" class="form-label">Question Type</label>
                            <select class="form-select @error('question_type') is-invalid @enderror" 
                                    id="question_type" 
                                    name="question_type" 
                                    required>
                                <option value="">Select question type</option>
                                <option value="multiple_choice" @selected(old('question_type') == 'multiple_choice')>
                                    Multiple Choice
                                </option>
                                <option value="single_answer" @selected(old('question_type') == 'single_answer')>
                                    Single Answer
                                </option>
                                <option value="true_false" @selected(old('question_type') == 'true_false')>
                                    True/False
                                </option>
                            </select>
                            @error('question_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="options-container" class="mb-3" style="display: none;">
                            <label class="form-label">Options</label>
                            <div id="options-list">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">{{ chr(65 + $i) }}</span>
                                        <input type="text" 
                                               class="form-control @error('options.' . $i) is-invalid @enderror" 
                                               name="options[]" 
                                               value="{{ old('options.' . $i) }}"
                                               placeholder="Option {{ chr(65 + $i) }}">
                                        @error('options.' . $i)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endfor
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()">
                                Add Option
                            </button>
                        </div>

                        <div id="true-false-container" class="mb-3" style="display: none;">
                            <label class="form-label">Correct Answer</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="correct_answer" id="true" value="true" @checked(old('correct_answer') === 'true')>
                                <label class="btn btn-outline-success" for="true">True</label>

                                <input type="radio" class="btn-check" name="correct_answer" id="false" value="false" @checked(old('correct_answer') === 'false')>
                                <label class="btn btn-outline-danger" for="false">False</label>
                            </div>
                        </div>

                        <div id="correct-answer-container" class="mb-3" style="display: none;">
                            <label for="correct_answer" class="form-label">Correct Answer</label>
                            <select class="form-select @error('correct_answer') is-invalid @enderror" 
                                    id="correct_answer" 
                                    name="correct_answer">
                                <option value="">Select correct answer</option>
                            </select>
                            @error('correct_answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="number" 
                                   class="form-control @error('points') is-invalid @enderror" 
                                   id="points" 
                                   name="points" 
                                   value="{{ old('points', 1) }}" 
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
                                      rows="2">{{ old('explanation') }}</textarea>
                            @error('explanation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Provide an explanation that will be shown after the quiz if review is enabled.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.questions', $quiz->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionTypeSelect = document.getElementById('question_type');
    const optionsContainer = document.getElementById('options-container');
    const trueFalseContainer = document.getElementById('true-false-container');
    const correctAnswerContainer = document.getElementById('correct-answer-container');
    
    questionTypeSelect.addEventListener('change', updateFormDisplay);
    updateFormDisplay();

    function updateFormDisplay() {
        const selectedType = questionTypeSelect.value;
        
        optionsContainer.style.display = 'none';
        trueFalseContainer.style.display = 'none';
        correctAnswerContainer.style.display = 'none';
        
        if (selectedType === 'multiple_choice' || selectedType === 'single_answer') {
            optionsContainer.style.display = 'block';
            correctAnswerContainer.style.display = 'block';
            updateCorrectAnswerOptions();
        } else if (selectedType === 'true_false') {
            trueFalseContainer.style.display = 'block';
        }
    }

    function updateCorrectAnswerOptions() {
        const correctAnswerSelect = document.getElementById('correct_answer');
        const options = document.querySelectorAll('input[name="options[]"]');
        correctAnswerSelect.innerHTML = '<option value="">Select correct answer</option>';
        
        options.forEach((option, index) => {
            if (option.value.trim()) {
                const letter = String.fromCharCode(65 + index);
                const opt = document.createElement('option');
                opt.value = index;
                opt.textContent = `${letter}) ${option.value}`;
                correctAnswerSelect.appendChild(opt);
            }
        });
    }

    // Update correct answer options when options change
    document.getElementById('options-list').addEventListener('input', updateCorrectAnswerOptions);
});

function addOption() {
    const optionsList = document.getElementById('options-list');
    const optionCount = optionsList.children.length;
    const letter = String.fromCharCode(65 + optionCount);
    
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <span class="input-group-text">${letter}</span>
        <input type="text" class="form-control" name="options[]" placeholder="Option ${letter}">
        <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    optionsList.appendChild(div);
    updateCorrectAnswerOptions();
}

function removeOption(button) {
    const optionsList = document.getElementById('options-list');
    button.closest('.input-group').remove();
    
    // Reorder remaining options
    const options = optionsList.children;
    for (let i = 0; i < options.length; i++) {
        const letter = String.fromCharCode(65 + i);
        options[i].querySelector('.input-group-text').textContent = letter;
        options[i].querySelector('input').placeholder = `Option ${letter}`;
    }
    
    updateCorrectAnswerOptions();
}
</script>
@endpush
@endsection
