// Quiz Timer and Auto-submission Logic
class QuizTimer {
    constructor(duration, updateCallback, expireCallback) {
        this.duration = duration * 60; // Convert minutes to seconds
        this.remainingTime = this.duration;
        this.updateCallback = updateCallback;
        this.expireCallback = expireCallback;
        this.timerId = null;
        this.startTime = null;
    }

    start() {
        this.startTime = Date.now() - ((this.duration - this.remainingTime) * 1000);
        this.timerId = setInterval(() => this.tick(), 1000);
        this.tick(); // Initial tick
    }

    pause() {
        if (this.timerId) {
            clearInterval(this.timerId);
            this.timerId = null;
        }
    }

    resume() {
        if (!this.timerId) {
            this.start();
        }
    }

    tick() {
        const now = Date.now();
        const elapsed = Math.floor((now - this.startTime) / 1000);
        this.remainingTime = Math.max(0, this.duration - elapsed);

        if (this.remainingTime === 0) {
            this.pause();
            this.expireCallback();
        } else {
            this.updateCallback(this.remainingTime);
        }
    }

    getFormattedTime() {
        const minutes = Math.floor(this.remainingTime / 60);
        const seconds = this.remainingTime % 60;
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
}

class QuizManager {
    constructor(quizId, options = {}) {
        this.quizId = quizId;
        this.options = {
            duration: 60, // Default 60 minutes
            autoSave: true,
            displayMode: 'one_by_one',
            ...options
        };
        this.currentQuestion = 0;
        this.answers = new Map();
        this.timer = null;
        this.autoSaveInterval = null;
    }

    init() {
        // Initialize timer
        this.timer = new QuizTimer(
            this.options.duration,
            (time) => this.updateTimerDisplay(time),
            () => this.autoSubmit()
        );

        // Setup auto-save if enabled
        if (this.options.autoSave) {
            this.autoSaveInterval = setInterval(() => this.saveProgress(), 30000); // Save every 30 seconds
        }

        // Initialize event listeners
        this.setupEventListeners();

        // Start timer
        this.timer.start();
    }

    setupEventListeners() {
        // Answer selection handling
        document.querySelectorAll('.quiz-answer').forEach(input => {
            input.addEventListener('change', (e) => this.handleAnswerSelection(e));
        });

        // Navigation buttons
        if (this.options.displayMode === 'one_by_one') {
            document.getElementById('nextQuestion')?.addEventListener('click', () => this.nextQuestion());
            document.getElementById('prevQuestion')?.addEventListener('click', () => this.prevQuestion());
        }

        // Submit button
        document.getElementById('submitQuiz')?.addEventListener('click', () => this.submitQuiz());
    }

    handleAnswerSelection(event) {
        const questionId = event.target.closest('.question-container').dataset.questionId;
        const answer = event.target.value;

        this.answers.set(questionId, answer);
        this.saveProgress();
    }

    async saveProgress() {
        try {
            const response = await fetch(`/ajax/quiz/${this.quizId}/save-answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    answers: Object.fromEntries(this.answers),
                    current_question: this.currentQuestion
                })
            });

            if (!response.ok) {
                console.error('Failed to save progress');
            }
        } catch (error) {
            console.error('Error saving progress:', error);
        }
    }

    async submitQuiz() {
        // Clear auto-save interval
        if (this.autoSaveInterval) {
            clearInterval(this.autoSaveInterval);
        }

        // Stop timer
        this.timer.pause();

        try {
            const response = await fetch(`/ajax/quiz/${this.quizId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    answers: Object.fromEntries(this.answers),
                    time_taken: this.options.duration * 60 - this.timer.remainingTime
                })
            });

            if (response.ok) {
                const result = await response.json();
                window.location.href = result.redirect_url;
            } else {
                console.error('Failed to submit quiz');
            }
        } catch (error) {
            console.error('Error submitting quiz:', error);
        }
    }

    autoSubmit() {
        this.submitQuiz();
    }

    updateTimerDisplay(remainingTime) {
        const timerElement = document.getElementById('quizTimer');
        if (timerElement) {
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            // Add warning class when less than 5 minutes remaining
            if (remainingTime < 300) {
                timerElement.classList.add('text-danger');
            }
        }
    }

    nextQuestion() {
        if (this.currentQuestion < document.querySelectorAll('.question-container').length - 1) {
            this.showQuestion(++this.currentQuestion);
        }
    }

    prevQuestion() {
        if (this.currentQuestion > 0) {
            this.showQuestion(--this.currentQuestion);
        }
    }

    showQuestion(index) {
        document.querySelectorAll('.question-container').forEach((container, idx) => {
            container.style.display = idx === index ? 'block' : 'none';
        });
        this.updateNavigationButtons();
    }

    updateNavigationButtons() {
        const totalQuestions = document.querySelectorAll('.question-container').length;
        
        if (document.getElementById('prevQuestion')) {
            document.getElementById('prevQuestion').disabled = this.currentQuestion === 0;
        }
        
        if (document.getElementById('nextQuestion')) {
            document.getElementById('nextQuestion').disabled = this.currentQuestion === totalQuestions - 1;
        }

        // Update progress indicator
        const progressElement = document.getElementById('questionProgress');
        if (progressElement) {
            progressElement.textContent = `Question ${this.currentQuestion + 1} of ${totalQuestions}`;
        }
    }
}
