@extends('layouts.app')

@section('content')
    <div class="admin-page-head">
        <div>
            <h2 class="mb-0">Manage Questions</h2>
        </div>
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary btn-admin">Add Question</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.questions.index') }}" class="admin-toolbar" id="questionFilterForm">
        <select name="topic_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Topics</option>
            @foreach($topics as $topic)
                <option value="{{ $topic->id }}" {{ (string) $selectedTopicId === (string) $topic->id ? 'selected' : '' }}>
                    {{ $topic->name }} ({{ $topic->category->name }})
                </option>
            @endforeach
        </select>

        <div class="admin-toolbar-spacer"></div>

        <div class="admin-toolbar-search">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search question text, options, explanation, topic, or category"
                value="{{ $search }}"
            >

            <div class="admin-toolbar-actions">
                <button type="submit" class="btn btn-outline-primary btn-admin">Search</button>
                @if($search !== '' || $selectedTopicId)
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-primary btn-admin">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if($questions->isEmpty())
        <div class="alert alert-info">
            No questions found.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Topic</th>
                                <th>Category</th>
                                <th>Correct</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>{{ $question->question_text }}</td>
                                    <td>{{ $question->topic->name }}</td>
                                    <td>{{ $question->topic->category->name }}</td>
                                    <td>{{ $question->correct_answer }}</td>
                                    <td>
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-outline-primary btn-admin">Edit</a>

                                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-admin btn-admin-danger"
                                                onclick="return confirm('Delete this question?')">
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
        </div>
    @endif
@endsection
