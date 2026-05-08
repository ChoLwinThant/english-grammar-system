@extends('layouts.app')

@section('content')
    <div class="admin-page-head">
        <div>
            <h2 class="mb-0">Manage Categories</h2>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-admin">Add Category</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.categories.index') }}" class="admin-toolbar">
        <div class="admin-toolbar-spacer"></div>
        <div class="admin-toolbar-search">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search category name or description"
                value="{{ $search }}"
            >
            <div class="admin-toolbar-actions">
                <button type="submit" class="btn btn-primary btn-admin">Search</button>
                @if($search !== '')
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary btn-admin">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if($categories->isEmpty())
        <div class="alert alert-info">
            No categories found.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            {{-- <th style="width: 10%">ID</th> --}}
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                {{-- <td>{{ $category->id }}</td> --}}
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?: '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary btn-admin">Edit</a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-admin btn-admin-danger"
                                            onclick="return confirm('Delete this category?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
