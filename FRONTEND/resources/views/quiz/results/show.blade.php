@extends('layouts.app')

@section('content')
<div class="quiz-results" id="quizResults">
    <!-- Results Header -->
    <div class="results-header">
        <div class="container">
            <div class="row align-items-center py-4">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-3">Quiz Results</h1>
                    <h2 class="quiz-title mb-0">@{{ quiz.title }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Overview -->
    <section class="score-overview py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="score-card card shadow-lg border-0">
                        <div class="card-body text-center p-5">
                            <!-- Circular Score Display -->
                            <div class="score-circle mx-auto mb-4"
                                 :style="{ '--score-percentage': score.percentage + '%' }">
                                <div class="score-circle-inner">
                                    <div class="score-value">@{{ score.percentage }}%</div>
                                    <div class="score-label">Score</div>
                                </div>
                            </div>

                            <!-- Score Details -->
                            <div class="score-details">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="score-stat">
                                            <i class="fas fa-check text-success"></i>
                                            <div class="stat-value">@{{ score.correct }}</div>
                                            <div class="stat-label">Correct</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="score-stat">
                                            <i class="fas fa-times text-danger"></i>
                                            <div class="stat-value">@{{ score.incorrect }}</div>
                                            <div class="stat-label">Incorrect</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="score-stat">
                                            <i class="fas fa-clock text-info"></i>
                                            <div class="stat-value">@{{ formatTime(quiz.timeTaken) }}</div>
                                            <div class="stat-label">Time Taken</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Performance Badge -->
                            <div class="performance-badge mt-4" :class="performanceBadgeClass">
                                <i :class="performanceIcon"></i>
                                @{{ performanceMessage }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Review (Optional) -->
    <section class="answer-review py-5 bg-light" v-if="quiz.reviewEnabled">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="review-header text-center mb-5">
                        <h3>Detailed Review</h3>
                        <p class="text-muted">Review your answers and learn from mistakes</p>
                    </div>

                    <!-- Questions Review -->
                    <div class="questions-review">
                        <transition-group name="fade" tag="div" class="row g-4">
                            <div class="col-12" v-for="(question, index) in quiz.questions" :key="index">
                                <div class="question-review-card card border-0 shadow-sm"
                                     :class="{'correct': isCorrect(index), 'incorrect': !isCorrect(index)}">
                                    <div class="card-body p-4">
                                        <!-- Question Status Icon -->
                                        <div class="question-status">
                                            <i class="fas"
                                               :class="[isCorrect(index) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger']">
                                            </i>
                                        </div>

                                        <!-- Question Content -->
                                        <div class="question-content">
                                            <h4 class="question-number mb-3">Question @{{ index + 1 }}</h4>
                                            <p class="question-text mb-4">@{{ question.text }}</p>

                                            <!-- Answer Display -->
                                            <div class="answer-comparison">
                                                <div class="user-answer mb-3">
                                                    <strong>Your Answer:</strong>
                                                    <div class="answer-display" :class="{'text-danger': !isCorrect(index)}">
                                                        @{{ formatAnswer(question, userAnswers[index]) }}
                                                    </div>
                                                </div>

                                                <div class="correct-answer" v-if="!isCorrect(index)">
                                                    <strong>Correct Answer:</strong>
                                                    <div class="answer-display text-success">
                                                        @{{ formatAnswer(question, question.correctAnswer) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Explanation (if available) -->
                                            <div class="answer-explanation mt-3" v-if="question.explanation">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    @{{ question.explanation }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Action Buttons -->
    <section class="results-actions py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <button class="btn btn-primary me-3" @click="retryQuiz">
                        <i class="fas fa-redo me-2"></i>Try Again
                    </button>
                    <button class="btn btn-outline-primary" @click="backToDashboard">
                        <i class="fas fa-home me-2"></i>Back to Dashboard
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
.quiz-results {
    background: #f8f9fa;
}

.results-header {
    background: white;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.quiz-title {
    color: #6c757d;
    font-weight: 300;
}

.score-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
}

.score-circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: conic-gradient(
        #28a745 var(--score-percentage),
        #e9ecef var(--score-percentage)
    );
    position: relative;
}

.score-circle-inner {
    position: absolute;
    top: 10px;
    left: 10px;
    right: 10px;
    bottom: 10px;
    background: white;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.score-value {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    color: #2c3e50;
}

.score-label {
    font-size: 1rem;
    color: #6c757d;
    margin-top: 0.5rem;
}

.score-stat {
    padding: 1.5rem;
    border-radius: 0.5rem;
    background: #f8f9fa;
    transition: transform 0.2s ease;
}

.score-stat:hover {
    transform: translateY(-5px);
}

.score-stat i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
}

.performance-badge {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 500;
}

.performance-badge.excellent {
    background: #d4edda;
    color: #155724;
}

.performance-badge.good {
    background: #fff3cd;
    color: #856404;
}

.performance-badge.needs-improvement {
    background: #f8d7da;
    color: #721c24;
}

.question-review-card {
    border-radius: 1rem;
    transition: transform 0.2s ease;
}

.question-review-card:hover {
    transform: translateY(-5px);
}

.question-review-card.correct {
    border-left: 4px solid #28a745;
}

.question-review-card.incorrect {
    border-left: 4px solid #dc3545;
}

.question-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 1.5rem;
}

.answer-display {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    margin-top: 0.5rem;
}

/* Animations */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s, transform 0.5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

@media (max-width: 768px) {
    .score-circle {
        width: 150px;
        height: 150px;
    }

    .score-value {
        font-size: 2rem;
    }

    .score-details .row {
        gap: 1rem !important;
    }

    .score-stat {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
new Vue({
    el: '#quizResults',
    data: {
        quiz: {
            title: 'Sample Quiz',
            questions: [],
            timeTaken: 1800,
            reviewEnabled: true
        },
        score: {
            correct: 15,
            incorrect: 5,
            percentage: 75
        },
        userAnswers: []
    },
    computed: {
        performanceBadgeClass() {
            if (this.score.percentage >= 90) return 'excellent';
            if (this.score.percentage >= 70) return 'good';
            return 'needs-improvement';
        },
        performanceIcon() {
            return {
                'fas fa-trophy': this.score.percentage >= 90,
                'fas fa-star': this.score.percentage >= 70,
                'fas fa-book': this.score.percentage < 70
            };
        },
        performanceMessage() {
            if (this.score.percentage >= 90) return 'Excellent Performance!';
            if (this.score.percentage >= 70) return 'Good Job!';
            return 'Keep Practicing!';
        }
    },
    methods: {
        isCorrect(index) {
            return this.quiz.questions[index].correctAnswer === this.userAnswers[index];
        },
        formatAnswer(question, answer) {
            switch (question.type) {
                case 'mcq':
                    return question.options[answer];
                case 'truefalse':
                    return answer ? 'True' : 'False';
                default:
                    return answer;
            }
        },
        formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}m ${remainingSeconds}s`;
        },
        retryQuiz() {
            // Implement retry logic
        },
        backToDashboard() {
            window.location.href = '/dashboard';
        },
        async fetchResults() {
            try {
                // Implement API call to fetch results
                const response = await fetch(`/api/quiz/${this.quizId}/results`);
                const data = await response.json();
                this.quiz = data.quiz;
                this.score = data.score;
                this.userAnswers = data.userAnswers;
            } catch (error) {
                console.error('Error fetching results:', error);
            }
        }
    },
    mounted() {
        this.fetchResults();
    }
});
</script>
@endpush
