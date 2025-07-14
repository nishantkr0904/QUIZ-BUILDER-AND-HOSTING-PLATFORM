@extends('layouts.app')

@section('title', 'Take Quiz')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Quiz Title</h2>
    <div class="mb-3">
        <span class="badge bg-info">Category: Science</span>
        <span class="badge bg-warning text-dark">Difficulty: Medium</span>
        <span class="badge bg-secondary">Time Left: <span id="timer">10:00</span></span>
    </div>
    <form id="quiz-form">
        {{-- Example question, replace with dynamic content --}}
        <div class="mb-4">
            <h5>1. What is the capital of France?</h5>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q1" id="q1a" value="A">
                <label class="form-check-label" for="q1a">A) Paris</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q1" id="q1b" value="B">
                <label class="form-check-label" for="q1b">B) London</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q1" id="q1c" value="C">
                <label class="form-check-label" for="q1c">C) Rome</label>
            </div>
        </div>
        {{-- End example --}}
        <button type="submit" class="btn btn-success">Submit Quiz</button>
    </form>
</div>
<script>
// Placeholder for timer and AJAX save logic
</script>
@endsection
