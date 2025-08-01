/**
 * Quiz Timer Module
 * Handles quiz timing functionality with modern UI and auto-submission
 */
export default class QuizTimer {
    constructor(options) {
        this.duration = options.duration || 0;
        this.timeLeft = options.timeLeft || this.duration;
        this.onTick = options.onTick || (() => {});
        this.onTimeUp = options.onTimeUp || (() => {});
        this.warningTime = options.warningTime || 300; // 5 minutes warning
        this.criticalTime = options.criticalTime || 60; // 1 minute critical
        this.interval = null;
        this.isPaused = false;
    }

    start() {
        if (this.interval) return;
        
        this.interval = setInterval(() => {
            if (!this.isPaused) {
                this.timeLeft--;
                this.updateUI();
                
                if (this.timeLeft <= 0) {
                    this.stop();
                    this.onTimeUp();
                }
            }
        }, 1000);

        this.updateUI();
    }

    pause() {
        this.isPaused = true;
        this.updateUI();
    }

    resume() {
        this.isPaused = false;
        this.updateUI();
    }

    stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }

    reset() {
        this.timeLeft = this.duration;
        this.updateUI();
    }

    updateUI() {
        const timeState = this.getTimeState();
        const formattedTime = this.formatTime(this.timeLeft);
        
        this.onTick({
            timeLeft: this.timeLeft,
            formattedTime,
            timeState,
            isPaused: this.isPaused
        });
    }

    getTimeState() {
        if (this.timeLeft <= this.criticalTime) return 'critical';
        if (this.timeLeft <= this.warningTime) return 'warning';
        return 'normal';
    }

    formatTime(seconds) {
        if (seconds < 0) return '00:00:00';
        
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        return [hours, minutes, secs]
            .map(v => v.toString().padStart(2, '0'))
            .join(':');
    }

    getTimeLeftPercentage() {
        return (this.timeLeft / this.duration) * 100;
    }
}
