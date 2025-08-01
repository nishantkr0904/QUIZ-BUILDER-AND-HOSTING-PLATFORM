<!-- Quiz Timer Component -->
<div class="quiz-timer" :class="[timeState, { 'paused': isPaused }]">
    <!-- Circular Progress -->
    <div class="timer-progress">
        <svg class="progress-ring" width="120" height="120">
            <circle class="progress-ring__circle-bg" 
                    stroke="#e9ecef"
                    stroke-width="8"
                    fill="transparent"
                    r="52"
                    cx="60"
                    cy="60"/>
            <circle class="progress-ring__circle" 
                    stroke="currentColor"
                    stroke-width="8"
                    fill="transparent"
                    r="52"
                    cx="60"
                    cy="60"
                    :stroke-dasharray="circumference"
                    :stroke-dashoffset="dashOffset"/>
        </svg>
        
        <!-- Timer Display -->
        <div class="timer-display">
            <div class="time">@{{ formattedTime }}</div>
            <div class="timer-label">Time Left</div>
        </div>
    </div>

    <!-- Warning Message -->
    <div class="timer-warning" v-if="showWarning">
        <div class="alert" :class="warningClass" role="alert">
            <i class="fas" :class="warningIcon"></i>
            @{{ warningMessage }}
        </div>
    </div>
</div>

<style>
.quiz-timer {
    --timer-normal: #28a745;
    --timer-warning: #ffc107;
    --timer-critical: #dc3545;
    
    position: relative;
    color: var(--timer-normal);
    transition: color 0.3s ease;
}

.quiz-timer.warning {
    color: var(--timer-warning);
}

.quiz-timer.critical {
    color: var(--timer-critical);
    animation: pulse 1s ease-in-out infinite;
}

.quiz-timer.paused .timer-progress {
    opacity: 0.7;
}

.timer-progress {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.progress-ring {
    transform: rotate(-90deg);
}

.progress-ring__circle {
    transition: stroke-dashoffset 0.3s ease;
}

.timer-display {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.time {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.timer-label {
    font-size: 0.75rem;
    opacity: 0.8;
}

.timer-warning {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: max-content;
    margin-top: 1rem;
}

.timer-warning .alert {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    margin: 0;
    animation: slideIn 0.3s ease-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .timer-progress {
        width: 100px;
        height: 100px;
    }

    .time {
        font-size: 1.25rem;
    }

    .timer-label {
        font-size: 0.7rem;
    }
}
</style>

@push('scripts')
<script>
Vue.component('quiz-timer', {
    props: {
        duration: {
            type: Number,
            required: true
        },
        timeLeft: {
            type: Number,
            required: true
        },
        isPaused: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            circumference: 2 * Math.PI * 52,
            warningThreshold: 300, // 5 minutes
            criticalThreshold: 60  // 1 minute
        };
    },
    computed: {
        timeState() {
            if (this.timeLeft <= this.criticalThreshold) return 'critical';
            if (this.timeLeft <= this.warningThreshold) return 'warning';
            return 'normal';
        },
        dashOffset() {
            const progress = this.timeLeft / this.duration;
            return this.circumference * (1 - progress);
        },
        formattedTime() {
            const hours = Math.floor(this.timeLeft / 3600);
            const minutes = Math.floor((this.timeLeft % 3600) / 60);
            const seconds = this.timeLeft % 60;
            
            if (hours > 0) {
                return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        },
        showWarning() {
            return this.timeState !== 'normal';
        },
        warningMessage() {
            if (this.timeState === 'critical') {
                return 'Less than 1 minute remaining!';
            }
            return '5 minutes remaining!';
        },
        warningClass() {
            return {
                'alert-warning': this.timeState === 'warning',
                'alert-danger': this.timeState === 'critical'
            };
        },
        warningIcon() {
            return {
                'fa-clock': this.timeState === 'warning',
                'fa-exclamation-circle': this.timeState === 'critical'
            };
        }
    },
    methods: {
        notifyTimeState() {
            if (this.timeLeft === this.warningThreshold || this.timeLeft === this.criticalThreshold) {
                // Play notification sound
                const audio = new Audio('/assets/sounds/timer-alert.mp3');
                audio.play().catch(() => {});
                
                // Vibrate on mobile devices
                if ('vibrate' in navigator) {
                    navigator.vibrate(200);
                }
            }
        }
    },
    watch: {
        timeLeft(newVal) {
            this.notifyTimeState();
        }
    }
});
</script>
@endpush
