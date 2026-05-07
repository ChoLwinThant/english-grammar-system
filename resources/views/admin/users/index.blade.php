@extends('layouts.app')

@section('content')
    <div class="admin-page-head">
        <div>
            <h2 class="mb-0">Manage Users</h2>
            <p>Search members by name or email, and filter them by role.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-admin">Add User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="admin-toolbar">
        <select name="role" class="form-select" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ $selectedRole === 'user' ? 'selected' : '' }}>User</option>
        </select>

        <div class="admin-toolbar-spacer"></div>

        <div class="admin-toolbar-search">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search user name or email"
                value="{{ $search }}"
            >
            <div class="admin-toolbar-actions">
                <button type="submit" class="btn btn-primary btn-admin">Search</button>
                @if($search !== '' || $selectedRole !== '')
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-admin">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if($users->isEmpty())
        <div class="alert alert-info">
            No users found.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Grammar Checks</th>
                                <th>Quiz Attempts</th>
                                <th>Joined</th>
                                <th style="width: 30%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-role text-capitalize">{{ $user->role }}</span>
                                    </td>
                                    <td>{{ $user->grammar_checks_count }}</td>
                                    <td>{{ $user->quiz_attempts_count }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.history', $user) }}" class="btn btn-outline-primary btn-admin">View History</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-admin">Edit</a>

                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-admin btn-admin-danger"
                                                    onclick="return confirm('Delete this user account?')"
                                                >
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Current account</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
