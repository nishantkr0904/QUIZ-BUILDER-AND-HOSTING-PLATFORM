@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Manage Users</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search users..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin" @selected(request('role') == 'admin')>Administrators</option>
                        <option value="user" @selected(request('role') == 'user')>Regular Users</option>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Completed Quizzes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="badge bg-primary">Administrator</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>{{ $user->completed_quizzes }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(Auth::id() !== $user->id)
                                            <button type="button" 
                                                    class="btn btn-sm {{ $user->is_admin ? 'btn-warning' : 'btn-success' }}"
                                                    title="{{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}"
                                                    onclick="confirmToggleAdmin({{ $user->id }}, '{{ $user->name }}', {{ $user->is_admin }})">
                                                <i class="fas {{ $user->is_admin ? 'fa-user' : 'fa-user-shield' }}"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <form id="toggle-admin-{{ $user->id }}"
                                          action="{{ route('admin.users.toggle-admin', $user->id) }}"
                                          method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No users found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmToggleAdmin(userId, userName, isAdmin) {
    const action = isAdmin ? 'remove admin privileges from' : 'make';
    const message = `Are you sure you want to ${action} ${userName} an administrator?`;
    
    if (confirm(message)) {
        document.getElementById(`toggle-admin-${userId}`).submit();
    }
}
</script>
@endpush
@endsection
