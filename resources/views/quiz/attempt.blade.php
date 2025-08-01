@extends('layouts.app')

@section('title', $quiz->title)

@section('styles')
<style>
.timer-container {
    position: sticky;
    top: 1rem;
    z-index: 100;
}

.timer {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.5rem;
    letter-spacing: 0.1em;
}

.timer.warning {
    color: #dc3545;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.question-nav {
    position: sticky;
    bottom: 1rem;
    z-index: 100;
}

.question-indicator {
    width: 2.5rem;
    height: 2.5rem;
    margin: 0.25rem;
    font-size: 0.875rem;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.question-indicator.current {
    transform: scale(1.1);
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #0d6efd;
}

.question-indicator.answered {
    background-color: #198754;
    color: white;
}

.question-indicator.not-answered {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.question-indicator.marked {
    background-color: #ffc107;
    color: black;
}

.progress {
    height: 0.5rem;
    border-radius: 0;
}

.option-card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.option-card:hover {
    transform: translateX(5px);
    border-color: #0d6efd;
}

.option-card.selected {
    background-color: #e7f1ff;
    border-color: #0d6efd;
}

.option-card.selected .option-check {
    color: #0d6efd;
}

.option-check {
    color: #dee2e6;
}
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Timer and Progress -->
    <div class="timer-container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                                <small class="text-muted">{{ $quiz->questions->count() }} Questions • {{ $quiz->duration }} Minutes</small>
                            </div>
                            <div class="timer" id="timer" data-duration="{{ $quiz->duration * 60 }}">
                                {{ $quiz->duration }}:00
                            </div>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Content -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="quiz-form" action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
                @csrf
                <div id="questions-container">
                    @foreach($quiz->questions as $index => $question)
                    <div class="question-slide" id="question-{{ $index + 1 }}" style="{{ $index > 0 ? 'display: none;' : '' }}">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                                    <div>
                                        <button type="button" 
                                                class="btn btn-outline-warning btn-sm mark-question"
                                                data-question="{{ $index + 1 }}">
                                            <i class="fas fa-flag"></i> Mark for Review
                                        </button>
                                    </div>
                                </div>
                                
                                <p class="card-text mb-4">{{ $question->question_text }}</p>

                                <div class="options-container">
                                    @if($question->question_type === 'multiple_choice')
                                        @foreach($question->options as $optionIndex => $option)
                                        <div class="card option-card mb-3" data-option="{{ $optionIndex }}">
                                            <div class="card-body py-2 px-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="option-check me-3">
                                                        <i class="far fa-circle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        {{ $option }}
                                                    </div>
                                                </div>
                                                <input type="radio" 
                                                       name="answers[{{ $question->id }}]" 
                                                       value="{{ $optionIndex }}"
                                                       class="d-none option-input">
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="answers[{{ $question->id }}]" 
                                                   id="true-{{ $question->id }}" value="true" autocomplete="off">
                                            <label class="btn btn-outline-success" for="true-{{ $question->id }}">True</label>

                                            <input type="radio" class="btn-check" name="answers[{{ $question->id }}]" 
                                                   id="false-{{ $question->id }}" value="false" autocomplete="off">
                                            <label class="btn btn-outline-danger" for="false-{{ $question->id }}">False</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Navigation Buttons -->
                <div class="question-nav">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-outline-primary" id="prev-btn" disabled>
                                    <i class="fas fa-chevron-left"></i> Previous
                                </button>
                                <div class="questions-progress">
                                    <span id="current-question">1</span>
                                    <span class="text-muted">/</span>
                                    <span id="total-questions">{{ $quiz->questions->count() }}</span>
                                </div>
                                <button type="button" class="btn btn-outline-primary" id="next-btn">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <!-- Question Indicators -->
                            <div class="text-center question-indicators">
                                @foreach($quiz->questions as $index => $question)
                                <div class="question-indicator not-answered d-inline-block" 
                                     data-question="{{ $index + 1 }}">
                                    {{ $index + 1 }}
                                </div>
                                @endforeach
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary" id="submit-quiz">
                                    Submit Quiz
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="submitConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit the quiz?</p>
                <div class="summary mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Questions:</span>
                        <span id="total-count">{{ $quiz->questions->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Answered:</span>
                        <span id="answered-count">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Marked for Review:</span>
                        <span id="marked-count">0</span>
                    </div>
                    <div class="d-flex justify-content-between text-danger">
                        <span>Not Attempted:</span>
                        <span id="not-attempted-count">{{ $quiz->questions->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-submit">Submit Quiz</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentQuestion = 1;
    const totalQuestions = {{ $quiz->questions->count() }};
    let timer;
    let timeLeft = {{ $quiz->duration * 60 }};
    let markedQuestions = new Set();
    let answeredQuestions = new Set();

    // Timer functionality
    function startTimer() {
        timer = setInterval(function() {
            timeLeft--;
            updateTimerDisplay();
            updateProgressBar();

            if (timeLeft <= 300) { // 5 minutes remaining
                document.getElementById('timer').classList.add('warning');
            }

            if (timeLeft <= 0) {
                clearInterval(timer);
                document.getElementById('quiz-form').submit();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('timer').textContent = 
            `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }

    function updateProgressBar() {
        const totalTime = {{ $quiz->duration * 60 }};
        const progress = ((totalTime - timeLeft) / totalTime) * 100;
        document.getElementById('progress-bar').style.width = `${progress}%`;
    }

    // Navigation functionality
    function showQuestion(number) {
        document.querySelectorAll('.question-slide').forEach(slide => {
            slide.style.display = 'none';
        });
        document.getElementById(`question-${number}`).style.display = 'block';
        currentQuestion = number;
        updateNavigationButtons();
        updateQuestionIndicator();
    }

    function updateNavigationButtons() {
        document.getElementById('prev-btn').disabled = currentQuestion === 1;
        document.getElementById('next-btn').disabled = currentQuestion === totalQuestions;
        document.getElementById('current-question').textContent = currentQuestion;
    }

    function updateQuestionIndicator() {
        document.querySelectorAll('.question-indicator').forEach(indicator => {
            indicator.classList.remove('current');
        });
        const currentIndicator = document.querySelector(`.question-indicator[data-question="${currentQuestion}"]`);
        currentIndicator.classList.add('current');
    }

    // Event Listeners
    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentQuestion > 1) {
            showQuestion(currentQuestion - 1);
        }
    });

    document.getElementById('next-btn').addEventListener('click', () => {
        if (currentQuestion < totalQuestions) {
            showQuestion(currentQuestion + 1);
        }
    });

    document.querySelectorAll('.question-indicator').forEach(indicator => {
        indicator.addEventListener('click', () => {
            showQuestion(parseInt(indicator.dataset.question));
        });
    });

    document.querySelectorAll('.option-card').forEach(card => {
        card.addEventListener('click', function() {
            const questionSlide = this.closest('.question-slide');
            const questionNumber = parseInt(questionSlide.id.split('-')[1]);
            
            // Update UI
            questionSlide.querySelectorAll('.option-card').forEach(c => {
                c.classList.remove('selected');
                c.querySelector('.option-check i').className = 'far fa-circle';
            });
            this.classList.add('selected');
            this.querySelector('.option-check i').className = 'fas fa-check-circle';
            
            // Select radio input
            this.querySelector('.option-input').checked = true;
            
            // Mark question as answered
            answeredQuestions.add(questionNumber);
            updateQuestionStatus(questionNumber);
            updateAnsweredCount();
        });
    });

    document.querySelectorAll('.mark-question').forEach(button => {
        button.addEventListener('click', function() {
            const questionNumber = parseInt(this.dataset.question);
            if (markedQuestions.has(questionNumber)) {
                markedQuestions.delete(questionNumber);
                this.classList.remove('active');
            } else {
                markedQuestions.add(questionNumber);
                this.classList.add('active');
            }
            updateQuestionStatus(questionNumber);
            updateMarkedCount();
        });
    });

    function updateQuestionStatus(questionNumber) {
        const indicator = document.querySelector(`.question-indicator[data-question="${questionNumber}"]`);
        indicator.classList.remove('not-answered', 'answered', 'marked');
        
        if (markedQuestions.has(questionNumber)) {
            indicator.classList.add('marked');
        } else if (answeredQuestions.has(questionNumber)) {
            indicator.classList.add('answered');
        } else {
            indicator.classList.add('not-answered');
        }
    }

    function updateCounts() {
        document.getElementById('answered-count').textContent = answeredQuestions.size;
        document.getElementById('marked-count').textContent = markedQuestions.size;
        document.getElementById('not-attempted-count').textContent = 
            totalQuestions - answeredQuestions.size;
    }

    // Submit handling
    document.getElementById('submit-quiz').addEventListener('click', function(e) {
        e.preventDefault();
        updateCounts();
        new bootstrap.Modal(document.getElementById('submitConfirmModal')).show();
    });

    document.getElementById('confirm-submit').addEventListener('click', function() {
        document.getElementById('quiz-form').submit();
    });

    // Save answers periodically
    function saveAnswers() {
        const formData = new FormData(document.getElementById('quiz-form'));
        fetch('{{ route("quiz.save-progress", $quiz->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    }

    setInterval(saveAnswers, 30000); // Save every 30 seconds

    // Initialize
    startTimer();
    updateQuestionIndicator();
});
</script>
@endpush
