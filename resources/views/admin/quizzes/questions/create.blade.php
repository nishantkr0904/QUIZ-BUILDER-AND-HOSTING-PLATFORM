@extends('layouts.admin')

@section('title', 'Add Question')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Add Question</h1>
            <p class="text-muted">Quiz: {{ $quiz->title }}</p>
        </div>
        <a href="{{ route('admin.quizzes.questions', $quiz->id) }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Questions
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.quizzes.questions.store', $quiz->id) }}" method="POST" id="questionForm">
                        @csrf
                        
                        <div class="mb-4">
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

                        <div class="mb-4">
                            <label for="question_type" class="form-label">Question Type</label>
                            <select class="form-select @error('question_type') is-invalid @enderror" 
                                    id="question_type" 
                                    name="question_type" 
                                    required>
                                <option value="">Select Type</option>
                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>
                                    Multiple Choice
                                </option>
                                <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>
                                    True/False
                                </option>
                            </select>
                            @error('question_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="multipleChoiceOptions" class="mb-4" style="display: none;">
                            <label class="form-label">Options</label>
                            <div class="options-container">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="option-row mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">{{ chr(65 + $i) }}</span>
                                            <input type="text" 
                                                   class="form-control @error('options.' . $i) is-invalid @enderror" 
                                                   name="options[]" 
                                                   value="{{ old('options.' . $i) }}"
                                                   placeholder="Option {{ chr(65 + $i) }}">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" 
                                                       type="radio" 
                                                       name="correct_answer" 
                                                       value="{{ $i }}"
                                                       {{ old('correct_answer') == $i ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        @error('options.' . $i)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div id="trueFalseOptions" class="mb-4" style="display: none;">
                            <label class="form-label">Correct Answer</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" 
                                       class="btn-check" 
                                       name="correct_answer" 
                                       id="true" 
                                       value="true" 
                                       {{ old('correct_answer') === 'true' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-success" for="true">True</label>

                                <input type="radio" 
                                       class="btn-check" 
                                       name="correct_answer" 
                                       id="false" 
                                       value="false" 
                                       {{ old('correct_answer') === 'false' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-danger" for="false">False</label>
                            </div>
                            @error('correct_answer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
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

                        <div class="mb-4">
                            <label for="explanation" class="form-label">
                                Explanation <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                      id="explanation" 
                                      name="explanation" 
                                      rows="3" 
                                      placeholder="Explain why the correct answer is right...">{{ old('explanation') }}</textarea>
                            @error('explanation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light">Reset</button>
                            <button type="submit" class="btn btn-primary">Add Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tips for Good Questions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-edit text-primary"></i> Writing Clear Questions</h6>
                        <ul class="small text-muted ps-4">
                            <li>Be clear and concise</li>
                            <li>Avoid double negatives</li>
                            <li>Test one concept per question</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-list text-success"></i> Multiple Choice Tips</h6>
                        <ul class="small text-muted ps-4">
                            <li>Make all options plausible</li>
                            <li>Keep option lengths similar</li>
                            <li>Avoid "All/None of the above"</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-check-square text-warning"></i> True/False Tips</h6>
                        <ul class="small text-muted ps-4">
                            <li>Avoid ambiguity</li>
                            <li>Use absolute statements</li>
                            <li>Keep statements brief</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionType = document.getElementById('question_type');
    const multipleChoiceOptions = document.getElementById('multipleChoiceOptions');
    const trueFalseOptions = document.getElementById('trueFalseOptions');

    function updateOptionsVisibility() {
        if (questionType.value === 'multiple_choice') {
            multipleChoiceOptions.style.display = 'block';
            trueFalseOptions.style.display = 'none';
        } else if (questionType.value === 'true_false') {
            multipleChoiceOptions.style.display = 'none';
            trueFalseOptions.style.display = 'block';
        } else {
            multipleChoiceOptions.style.display = 'none';
            trueFalseOptions.style.display = 'none';
        }
    }

    questionType.addEventListener('change', updateOptionsVisibility);
    updateOptionsVisibility();
});
</script>
@endpush
