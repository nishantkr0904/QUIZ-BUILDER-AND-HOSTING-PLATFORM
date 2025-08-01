@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0">Quiz Results</h1>
                        <a href="{{ route('user.quizzes') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to My Quizzes
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">{{ $quiz->title }}</h5>
                            <p class="text-muted mb-0">Category: {{ $quiz->category->name }}</p>
                            <p class="text-muted">Difficulty: {{ ucfirst($quiz->difficulty) }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="h2 mb-0 {{ $result->score >= $quiz->passing_score ? 'text-success' : 'text-danger' }}">
                                {{ $result->score }}/{{ $quiz->questions->count() }}
                                @if($quiz->questions->count() > 0)
                                    ({{ number_format(($result->score / $quiz->questions->count()) * 100, 1) }}%)
                                @else
                                    (0%)
                                @endif
                            </div>
                            <p class="text-muted">
                                @if($quiz->questions->count() > 0)
                                    {{ $result->score >= $quiz->passing_score ? 'Passed' : 'Failed' }}
                                    (Passing Score: {{ $quiz->passing_score }}%)
                                @else
                                    No questions available
                                @endif
                            </p>
                            <p class="small text-muted">
                                Completed on {{ $result->completed_at->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6 class="text-muted mb-2">Time Taken</h6>
                                <p class="h4 mb-0">{{ floor($result->time_taken / 60) }}m {{ $result->time_taken % 60 }}s</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6 class="text-muted mb-2">Correct Answers</h6>
                                <p class="h4 text-success mb-0">{{ $result->score }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6 class="text-muted mb-2">Incorrect Answers</h6>
                                <p class="h4 text-danger mb-0">{{ $quiz->questions->count() > 0 ? $quiz->questions->count() - $result->score : 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($quiz->review_enabled || Auth::user()->is_admin)
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Question Review</h2>
                    </div>
                    <div class="card-body">
                        @foreach($quiz->questions as $index => $question)
                            @php
                                $questionDetails = isset($result->details[$question->id]) ? $result->details[$question->id] : [];
                                $isCorrect = isset($questionDetails['correct']) ? $questionDetails['correct'] : false;
                            @endphp
                            <div class="question-review mb-4 p-3 border rounded {{ $isCorrect ? 'border-success' : 'border-danger' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h3 class="h6 mb-0">Question {{ $index + 1 }}</h3>
                                    <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }}">
                                        {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                    </span>
                                </div>

                                <p class="mb-3">{{ $question->text }}</p>

                                @if(isset($question->options) && is_array($question->options))
                                    @if($question->type === 'true_false')
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" disabled
                                                       {{ isset($questionDetails['answer']) && $questionDetails['answer'] === 'true' ? 'checked' : '' }}>
                                                <label class="form-check-label {{ $question->correct_answer === 'true' ? 'text-success fw-bold' : '' }}">
                                                    True
                                                    @if($question->correct_answer === 'true')
                                                        <i class="fas fa-check text-success ms-1"></i>
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" disabled
                                                       {{ isset($questionDetails['answer']) && $questionDetails['answer'] === 'false' ? 'checked' : '' }}>
                                                <label class="form-check-label {{ $question->correct_answer === 'false' ? 'text-success fw-bold' : '' }}">
                                                    False
                                                    @if($question->correct_answer === 'false')
                                                        <i class="fas fa-check text-success ms-1"></i>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        @foreach($question->options as $key => $option)
                                            <div class="form-check mb-2">
                                                <input type="radio" class="form-check-input" disabled
                                                    {{ isset($questionDetails['answer']) && $questionDetails['answer'] == $key ? 'checked' : '' }}
                                                    {{ $question->correct_answer == $key ? 'data-correct' : '' }}>
                                                <label class="form-check-label {{ $question->correct_answer == $key ? 'text-success fw-bold' : '' }}">
                                                    {{ chr(65 + $key) }}) {{ $option }}
                                                    @if($question->correct_answer == $key)
                                                        <i class="fas fa-check text-success ms-1"></i>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                @else
                                    <p class="text-muted">No options available for this question.</p>
                                @endif

                                @if($question->explanation)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <strong>Explanation:</strong>
                                        <p class="mb-0">{{ $question->explanation }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Answer review is not enabled for this quiz.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
