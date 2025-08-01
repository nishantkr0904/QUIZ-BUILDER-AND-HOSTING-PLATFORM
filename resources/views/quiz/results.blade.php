@extends('layouts.app')

@section('title', 'Quiz Results')

@section('styles')
<style>
.score-circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    color: white;
    position: relative;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
}

.score-percentage {
    font-size: 3rem;
    font-weight: bold;
    line-height: 1;
}

.score-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.stats-card {
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.question-card {
    border-radius: 0.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.question-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.question-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: bold;
}

.answer-option {
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    border-radius: 0.375rem;
    position: relative;
    transition: all 0.2s;
}

.answer-option.correct {
    background-color: rgba(25, 135, 84, 0.1);
    border: 1px solid #198754;
}

.answer-option.incorrect {
    background-color: rgba(220, 53, 69, 0.1);
    border: 1px solid #dc3545;
}

.answer-option.user-selected {
    background-color: rgba(13, 110, 253, 0.1);
    border: 1px solid #0d6efd;
}

.answer-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
}

.explanation-card {
    background-color: #f8f9fa;
    border-left: 4px solid #0dcaf0;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}
</style>
@endsection

@section('content')
<div class="container py-5">
    <!-- Score Overview -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <h1 class="display-5 mb-4">Quiz Results</h1>
            <div class="d-flex justify-content-center mb-4">
                <div class="score-circle animate-fade-in">
                    <div class="score-percentage">{{ round($attempt->score_percentage) }}%</div>
                    <div class="score-label">Score</div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row g-4 justify-content-center">
                <div class="col-sm-4">
                    <div class="card stats-card bg-success text-white animate-fade-in" style="animation-delay: 0.1s">
                        <div class="card-body">
                            <h5 class="card-title">Correct</h5>
                            <p class="display-6 mb-0">{{ $attempt->correct_answers }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card stats-card bg-danger text-white animate-fade-in" style="animation-delay: 0.2s">
                        <div class="card-body">
                            <h5 class="card-title">Incorrect</h5>
                            <p class="display-6 mb-0">{{ $attempt->incorrect_answers }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card stats-card bg-primary text-white animate-fade-in" style="animation-delay: 0.3s">
                        <div class="card-body">
                            <h5 class="card-title">Time Taken</h5>
                            <p class="display-6 mb-0">{{ $attempt->time_taken }} min</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($quiz->show_answers)
    <!-- Detailed Review -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detailed Review</h2>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary active" data-filter="all">
                        All Questions
                    </button>
                    <button type="button" class="btn btn-outline-success" data-filter="correct">
                        Correct Only
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-filter="incorrect">
                        Incorrect Only
                    </button>
                </div>
            </div>

            @foreach($questions as $index => $question)
            <div class="question-card card mb-4 animate-fade-in" 
                 style="animation-delay: {{ ($index + 6) * 0.1 }}s"
                 data-status="{{ $attempt->isCorrect($question->id) ? 'correct' : 'incorrect' }}">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="question-number {{ $attempt->isCorrect($question->id) ? 'bg-success' : 'bg-danger' }} text-white me-3">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-grow-1">{{ $question->question_text }}</div>
                        @if($attempt->isCorrect($question->id))
                            <span class="badge bg-success ms-2">Correct</span>
                        @else
                            <span class="badge bg-danger ms-2">Incorrect</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    @if($question->question_type === 'multiple_choice')
                        @foreach($question->options as $key => $option)
                            <div class="answer-option 
                                {{ $key == $question->correct_answer ? 'correct' : '' }}
                                {{ $key == $attempt->getAnswer($question->id) ? 'user-selected' : '' }}">
                                {{ $option }}
                                @if($key == $question->correct_answer)
                                    <span class="answer-icon text-success">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @elseif($key == $attempt->getAnswer($question->id) && $attempt->getAnswer($question->id) != $question->correct_answer)
                                    <span class="answer-icon text-danger">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="btn-group w-100">
                            <input type="radio" class="btn-check" disabled
                                   {{ $question->correct_answer ? 'checked' : '' }}>
                            <label class="btn {{ $question->correct_answer ? 'btn-success' : 'btn-outline-success' }}">
                                True
                            </label>

                            <input type="radio" class="btn-check" disabled
                                   {{ !$question->correct_answer ? 'checked' : '' }}>
                            <label class="btn {{ !$question->correct_answer ? 'btn-danger' : 'btn-outline-danger' }}">
                                False
                            </label>
                        </div>
                    @endif

                    @if($question->explanation)
                        <div class="explanation-card mt-3 p-3">
                            <h6 class="mb-2"><i class="fas fa-info-circle text-info"></i> Explanation</h6>
                            <p class="mb-0">{{ $question->explanation }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-8 text-center">
            <a href="{{ route('quiz.index') }}" class="btn btn-primary me-2">
                <i class="fas fa-home"></i> Back to Quizzes
            </a>
            @if($quiz->allow_retake)
                <a href="{{ route('quiz.start', $quiz->id) }}" class="btn btn-outline-primary">
                    <i class="fas fa-redo"></i> Retake Quiz
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter questions
    const filterButtons = document.querySelectorAll('[data-filter]');
    const questionCards = document.querySelectorAll('.question-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter questions
            questionCards.forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Print Results
    document.getElementById('print-results')?.addEventListener('click', function() {
        window.print();
    });
});
</script>
@endpush
