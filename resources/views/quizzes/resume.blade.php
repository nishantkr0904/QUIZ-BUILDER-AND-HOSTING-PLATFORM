@extends('layouts.app')

@section('title', 'Resume Quiz')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">{{ $quiz->title }}</h2>
                </div>
                <div class="card-body">
                    <p class="lead">Resuming your previous attempt</p>
                    
                    <form id="quiz-form" method="POST" action="{{ route('ajax.quiz.submit', $quiz->id) }}">
                        @csrf
                        @foreach($quiz->questions as $question)
                        <div class="question-container mb-4" data-question-id="{{ $question->id }}">
                            <h5 class="mb-3">{{ $question->text }}</h5>
                            <div class="options">
                                @foreach(json_decode($question->options) as $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" 
                                        name="answers[{{ $question->id }}]" 
                                        value="{{ $option }}"
                                        {{ $result->answers && isset($result->answers[$question->id]) && $result->answers[$question->id] === $option ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label">
                                        {{ $option }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('quiz-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get all answers
    const formData = new FormData(this);
    
    // Submit the quiz
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the quiz. Please try again.');
    });
});
</script>
@endpush
@endsection
