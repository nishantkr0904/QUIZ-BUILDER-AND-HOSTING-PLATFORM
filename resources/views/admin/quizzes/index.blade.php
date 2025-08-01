@extends('layouts.app')

@section('title', 'Manage Quizzes')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Manage Quizzes</h1>
        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Quiz
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="difficulty" class="form-select">
                        <option value="">All Difficulties</option>
                        <option value="easy" @selected(request('difficulty') == 'easy')>Easy</option>
                        <option value="medium" @selected(request('difficulty') == 'medium')>Medium</option>
                        <option value="hard" @selected(request('difficulty') == 'hard')>Hard</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Questions</th>
                            <th>Duration</th>
                            <th>Difficulty</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->title }}</td>
                                <td>{{ $quiz->category->name }}</td>
                                <td>{{ $quiz->questions_count ?? '0' }}</td>
                                <td>{{ $quiz->duration }} minutes</td>
                                <td>
                                    <span class="badge bg-{{ $quiz->difficulty === 'easy' ? 'success' : ($quiz->difficulty === 'medium' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($quiz->difficulty) }}
                                    </span>
                                </td>
                                <td>
                                    @if($quiz->isAvailable())
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.questions', $quiz->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Manage Questions">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit Quiz">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                title="Delete Quiz"
                                                onclick="confirmDelete({{ $quiz->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $quiz->id }}" 
                                          action="{{ route('admin.quizzes.destroy', $quiz->id) }}" 
                                          method="POST" 
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No quizzes found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($quizzes->hasPages())
            <div class="card-footer">
                {{ $quizzes->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(quizId) {
    if (confirm('Are you sure you want to delete this quiz? This action cannot be undone.')) {
        document.getElementById(`delete-form-${quizId}`).submit();
    }
}
</script>
@endpush
@endsection
