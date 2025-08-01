@extends('layouts.app')

@section('content')
<div class="quiz-container" id="quizApp">
    <!-- Quiz Header -->
    <div class="quiz-header shadow-sm">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col">
                    <h1 class="quiz-title mb-0">@{{ quiz.title }}</h1>
                </div>
                <div class="col-auto">
                    <div class="timer-container">
                        <i class="fas fa-clock"></i>
                        <span class="timer">@{{ formatTime(timeLeft) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="quiz-progress">
        <div class="progress" style="height: 6px;">
            <div class="progress-bar" :style="{ width: progress + '%' }" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <!-- Quiz Content -->
    <div class="container py-4">
        <div class="quiz-content">
            <!-- Question Navigation (For full form mode) -->
            <div class="question-nav mb-4" v-if="displayMode === 'full'">
                <div class="btn-group">
                    <button v-for="(q, index) in questions" 
                            :key="index"
                            class="btn"
                            :class="[
                                'btn-outline-primary',
                                {'active': currentQuestion === index},
                                {'answered': isQuestionAnswered(index)}
                            ]"
                            @click="jumpToQuestion(index)">
                        @{{ index + 1 }}
                    </button>
                </div>
            </div>

            <!-- Question Display -->
            <div class="question-container" :class="{ 'sliding': isSliding }">
                <transition name="fade">
                    <div class="question-card card shadow-sm" :key="currentQuestion">
                        <div class="card-body">
                            <div class="question-number mb-3">
                                Question @{{ currentQuestion + 1 }} of @{{ questions.length }}
                            </div>
                            
                            <h2 class="question-text mb-4">@{{ currentQuestionData.text }}</h2>

                            <!-- Multiple Choice Question -->
                            <div v-if="currentQuestionData.type === 'mcq'" class="options-grid">
                                <div v-for="(option, index) in currentQuestionData.options" 
                                     :key="index"
                                     class="option-item">
                                    <input :type="currentQuestionData.multiple ? 'checkbox' : 'radio'"
                                           :id="'option' + index"
                                           :name="'question' + currentQuestion"
                                           :value="index"
                                           v-model="answers[currentQuestion]"
                                           class="option-input">
                                    <label :for="'option' + index" class="option-label">
                                        @{{ option }}
                                    </label>
                                </div>
                            </div>

                            <!-- True/False Question -->
                            <div v-else-if="currentQuestionData.type === 'truefalse'" class="true-false-container">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           :id="'tf' + currentQuestion"
                                           v-model="answers[currentQuestion]">
                                    <label class="form-check-label" :for="'tf' + currentQuestion">
                                        True/False
                                    </label>
                                </div>
                            </div>

                            <!-- Single Answer Question -->
                            <div v-else class="single-answer-container">
                                <input type="text" 
                                       class="form-control" 
                                       v-model="answers[currentQuestion]"
                                       placeholder="Enter your answer...">
                            </div>
                        </div>
                    </div>
                </transition>
            </div>

            <!-- Navigation Buttons -->
            <div class="navigation-buttons mt-4">
                <div class="row justify-content-between">
                    <div class="col">
                        <button class="btn btn-outline-primary" 
                                @click="previousQuestion"
                                :disabled="currentQuestion === 0">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" 
                                @click="nextQuestion"
                                v-if="currentQuestion < questions.length - 1">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                        <button class="btn btn-success" 
                                @click="submitQuiz"
                                v-else>
                            Submit Quiz <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.quiz-container {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.quiz-header {
    background: white;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.quiz-title {
    font-size: 1.5rem;
    color: #2c3e50;
}

.timer-container {
    background: #e9ecef;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    color: #2c3e50;
}

.timer {
    font-weight: 600;
    margin-left: 0.5rem;
}

.question-nav .btn {
    margin: 0 0.2rem;
    min-width: 40px;
    height: 40px;
    border-radius: 50%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.question-nav .btn.answered {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

.question-card {
    border: none;
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.question-number {
    color: #6c757d;
    font-size: 0.9rem;
}

.question-text {
    font-size: 1.25rem;
    color: #2c3e50;
}

.options-grid {
    display: grid;
    gap: 1rem;
    margin-top: 2rem;
}

.option-item {
    position: relative;
}

.option-input {
    position: absolute;
    opacity: 0;
}

.option-label {
    display: block;
    padding: 1rem;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.option-input:checked + .option-label {
    border-color: #007bff;
    background-color: #f8f9ff;
}

.option-label:hover {
    border-color: #007bff;
}

.true-false-container {
    margin-top: 2rem;
}

.form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
    margin-top: 0.25rem;
}

.single-answer-container {
    margin-top: 2rem;
}

.navigation-buttons .btn {
    padding: 0.75rem 1.5rem;
}

/* Animations */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.sliding .question-card {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(50px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .quiz-title {
        font-size: 1.25rem;
    }

    .timer-container {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
    }

    .question-nav .btn {
        min-width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }

    .question-text {
        font-size: 1.1rem;
    }

    .option-label {
        padding: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
const quizApp = new Vue({
    el: '#quizApp',
    data: {
        quiz: {
            title: 'Sample Quiz Title',
            duration: 3600 // in seconds
        },
        questions: [
            // Sample questions array - will be populated from backend
            {
                text: 'What is the capital of France?',
                type: 'mcq',
                options: ['London', 'Berlin', 'Paris', 'Madrid'],
                multiple: false
            }
        ],
        currentQuestion: 0,
        answers: [],
        timeLeft: 3600,
        displayMode: 'single', // 'single' or 'full'
        isSliding: false
    },
    computed: {
        progress() {
            return ((this.currentQuestion + 1) / this.questions.length) * 100;
        },
        currentQuestionData() {
            return this.questions[this.currentQuestion];
        }
    },
    methods: {
        nextQuestion() {
            if (this.currentQuestion < this.questions.length - 1) {
                this.isSliding = true;
                this.currentQuestion++;
                this.saveProgress();
                setTimeout(() => this.isSliding = false, 300);
            }
        },
        previousQuestion() {
            if (this.currentQuestion > 0) {
                this.isSliding = true;
                this.currentQuestion--;
                setTimeout(() => this.isSliding = false, 300);
            }
        },
        jumpToQuestion(index) {
            this.currentQuestion = index;
        },
        isQuestionAnswered(index) {
            return this.answers[index] !== undefined && this.answers[index] !== '';
        },
        formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        },
        saveProgress() {
            localStorage.setItem('quizProgress', JSON.stringify({
                answers: this.answers,
                currentQuestion: this.currentQuestion,
                timeLeft: this.timeLeft
            }));
        },
        submitQuiz() {
            // Implement submission logic
            console.log('Quiz submitted:', this.answers);
        },
        startTimer() {
            const timer = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    clearInterval(timer);
                    this.submitQuiz();
                }
            }, 1000);
        }
    },
    created() {
        // Load saved progress if exists
        const savedProgress = localStorage.getItem('quizProgress');
        if (savedProgress) {
            const progress = JSON.parse(savedProgress);
            this.answers = progress.answers;
            this.currentQuestion = progress.currentQuestion;
            this.timeLeft = progress.timeLeft;
        }
        this.startTimer();

        // Handle keyboard navigation
        window.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === 'n') {
                this.nextQuestion();
            } else if (e.key === 'ArrowLeft' || e.key === 'p') {
                this.previousQuestion();
            }
        });
    }
});
</script>
@endpush
