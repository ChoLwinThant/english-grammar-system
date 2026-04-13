@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Questions</h2>
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">Add Question</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-warning">Edit</a>

                                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
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