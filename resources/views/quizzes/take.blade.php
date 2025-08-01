@extends('layouts.app')

@section('title', 'Take Quiz')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">{{ $quiz->title }}</h2>
    <div class="mb-3">
        <span class="badge bg-info">Category: {{ $quiz->category->name }}</span>
        <span class="badge bg-warning text-dark">Difficulty: {{ ucfirst($quiz->difficulty) }}</span>
        <span class="badge bg-secondary">Time Left: <span id="timer">{{ sprintf('%02d:00', intval($quiz->duration)) }}</span></span>
    </div>
    <form id="quiz-form" method="POST" action="{{ route('quiz.submit', $quiz->id) }}">
        @csrf
        <input type="hidden" name="start_time" id="start_time" value="{{ now()->toDateTimeString() }}">
        <input type="hidden" name="remaining_time" id="remaining_time" value="{{ intval($quiz->duration) * 60 }}">
        
        @if($quiz->questions && count($quiz->questions) > 0)
            @foreach($quiz->questions as $index => $question)
            <div class="question mb-4" data-question-id="{{ $question->id }}">
                <h5>{{ $index + 1 }}. {{ $question->text }}</h5>
                @if($question->type === 'single_answer')
                    <div class="form-group">
                        <input type="text" 
                               class="form-control" 
                               name="answers[{{ $question->id }}]" 
                               id="q{{ $question->id }}answer"
                               placeholder="Type your answer here..."
                               autocomplete="off">
                        <small class="form-text text-muted">Enter your answer in the box above. Be precise with your response.</small>
                    </div>
                @elseif($question->options && is_array($question->options))
                    @foreach($question->options as $key => $option)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                            name="answers[{{ $question->id }}]" 
                            id="q{{ $question->id }}opt{{ $key }}" 
                            value="{{ $key }}">
                        <label class="form-check-label" for="q{{ $question->id }}opt{{ $key }}">
                            {{ chr(65 + $key) }}) {{ $option }}
                        </label>
                    </div>
                    @endforeach
                @else
                    <p class="text-danger">Question type or options not properly configured.</p>
                @endif
            </div>
            @endforeach
        @else
            <div class="alert alert-warning">
                No questions are available for this quiz yet. Please try again later.
            </div>
        @endif
        
        <button type="submit" class="btn btn-success" id="submit-quiz">Submit Quiz</button>
    </form>
</div>

@push('scripts')
<script>
let timeLeft = parseInt('{{ intval($quiz->duration) }}', 10) * 60; // Convert minutes to seconds
const startTime = new Date();
let timerInterval;

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const timerDisplay = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    document.getElementById('timer').textContent = timerDisplay;
    document.getElementById('remaining_time').value = String(timeLeft);
    
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        document.getElementById('quiz-form').submit();
    }
    timeLeft--;
}

// Start timer
timerInterval = setInterval(updateTimer, 1000);

// Handle form submission
document.getElementById('quiz-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearInterval(timerInterval);
    
    // Calculate time taken
    const endTime = new Date();
    const timeTaken = Math.floor((endTime - startTime) / 1000);
    
    // Add time taken to form
    const timeInput = document.createElement('input');
    timeInput.type = 'hidden';
    timeInput.name = 'time_taken';
    timeInput.value = timeTaken;
    this.appendChild(timeInput);
    
    // Submit the form
    this.submit();
});
</script>
@endpush
@endsection
