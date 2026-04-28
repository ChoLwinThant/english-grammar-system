@extends('layouts.app')

@section('content')
    <style>
        .topic-difficulty-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.24rem 0.58rem;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border: 1px solid transparent;
            line-height: 1.1;
        }

        .topic-difficulty-basic {
            background: #ecfdf3;
            color: #166534;
            border-color: #86efac;
        }

        .topic-difficulty-elementary {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #93c5fd;
        }

        .topic-difficulty-intermediate {
            background: #fff7ed;
            color: #c2410c;
            border-color: #fdba74;
        }

        .topic-difficulty-advanced {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fca5a5;
        }
    </style>

    <div class="admin-page-head">
        <div>
            <h2 class="mb-0">Manage Topics</h2>
            <p>Search topics by name, difficulty, description, or related category.</p>
        </div>
        <a href="{{ route('admin.topics.create') }}" class="btn btn-primary btn-admin">Add Topic</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.topics.index') }}" class="admin-toolbar">
        <div class="admin-toolbar-spacer"></div>
        <div class="admin-toolbar-search">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search topic, description, or category"
                value="{{ $search }}"
            >
            <div class="admin-toolbar-actions">
                <button type="submit" class="btn btn-primary btn-admin">Search</button>
                @if($search !== '')
                    <a href="{{ route('admin.topics.index') }}" class="btn btn-outline-primary btn-admin">Clear</a>
                @endif
            </div>
        </div>
    </form>

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
                            <th>Difficulty</th>
                            <th>Description</th>
                            <th style="width: 25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topics as $topic)
                            <tr>
                                <td>{{ $topic->name }}</td>
                                <td>{{ $topic->category->name }}</td>
                                <td>
                                    <span class="topic-difficulty-badge topic-difficulty-{{ $topic->difficulty }}">
                                        {{ $topic->difficultyLabel() }}
                                    </span>
                                </td>
                                <td>{{ $topic->description ?: '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-outline-primary btn-admin">Edit</a>

                                    <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-admin btn-admin-danger"
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
