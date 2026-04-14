@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Topics</h2>
        <a href="{{ route('admin.topics.create') }}" class="btn btn-primary">Add Topic</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($topics->isEmpty())
        <div class="alert alert-info">
            No topics found.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Topic Name</th>
                            <th>Category</th>
                            <th style="width: 25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topics as $topic)
                            <tr>
                                <td>{{ $topic->name }}</td>
                                <td>{{ $topic->category->name }}</td>
                                <td>
                                    <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this topic?')">
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