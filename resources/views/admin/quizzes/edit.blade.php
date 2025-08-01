@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h4 mb-0">Edit Quiz</h1>
                    <a href="{{ route('admin.questions', $quiz->id) }}" class="btn btn-primary btn-sm">
                        Manage Questions
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Quiz Title</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $quiz->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $quiz->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="difficulty" class="form-label">Difficulty Level</label>
                            <select class="form-select @error('difficulty') is-invalid @enderror" 
                                    id="difficulty" 
                                    name="difficulty" 
                                    required>
                                <option value="">Select difficulty</option>
                                <option value="easy" @selected(old('difficulty', $quiz->difficulty) == 'easy')>Easy</option>
                                <option value="medium" @selected(old('difficulty', $quiz->difficulty) == 'medium')>Medium</option>
                                <option value="hard" @selected(old('difficulty', $quiz->difficulty) == 'hard')>Hard</option>
                            </select>
                            @error('difficulty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Duration (minutes)</label>
                                <input type="number" 
                                       class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" 
                                       name="duration" 
                                       value="{{ old('duration', $quiz->duration) }}" 
                                       min="1" 
                                       required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="passing_score" class="form-label">Passing Score (%)</label>
                                <input type="number" 
                                       class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" 
                                       name="passing_score" 
                                       value="{{ old('passing_score', $quiz->passing_score) }}" 
                                       min="0" 
                                       max="100" 
                                       required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="display_mode" class="form-label">Display Mode</label>
                            <select class="form-select @error('display_mode') is-invalid @enderror" 
                                    id="display_mode" 
                                    name="display_mode">
                                <option value="one_by_one" @selected(old('display_mode', $quiz->display_mode) == 'one_by_one')>
                                    One question at a time
                                </option>
                                <option value="full_form" @selected(old('display_mode', $quiz->display_mode) == 'full_form')>
                                    All questions at once
                                </option>
                            </select>
                            @error('display_mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="availability_start" class="form-label">Available From</label>
                                <input type="datetime-local" 
                                       class="form-control @error('availability_start') is-invalid @enderror" 
                                       id="availability_start" 
                                       name="availability_start" 
                                       value="{{ old('availability_start', $quiz->availability_start?->format('Y-m-d\TH:i')) }}">
                                @error('availability_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="availability_end" class="form-label">Available Until</label>
                                <input type="datetime-local" 
                                       class="form-control @error('availability_end') is-invalid @enderror" 
                                       id="availability_end" 
                                       name="availability_end" 
                                       value="{{ old('availability_end', $quiz->availability_end?->format('Y-m-d\TH:i')) }}">
                                @error('availability_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input @error('review_enabled') is-invalid @enderror" 
                                       id="review_enabled" 
                                       name="review_enabled" 
                                       value="1" 
                                       @checked(old('review_enabled', $quiz->review_enabled))>
                                <label class="form-check-label" for="review_enabled">
                                    Enable answer review after completion
                                </label>
                                @error('review_enabled')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input @error('randomize_questions') is-invalid @enderror" 
                                       id="randomize_questions" 
                                       name="randomize_questions" 
                                       value="1" 
                                       @checked(old('randomize_questions', $quiz->randomize_questions))>
                                <label class="form-check-label" for="randomize_questions">
                                    Randomize question order
                                </label>
                                @error('randomize_questions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.quizzes') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
