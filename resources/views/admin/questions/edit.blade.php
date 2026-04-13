@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-4">Edit Question</h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.questions.update', $question) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="topic_id" class="form-label">Topic</label>
                    <select name="topic_id" id="topic_id" class="form-select">
                        <option value="">Select Topic</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('topic_id', $question->topic_id) == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }} ({{ $topic->category->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="question_text" class="form-label">Question Text</label>
                    <textarea name="question_text" id="question_text" rows="3" class="form-control">{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="option_a" class="form-label">Option A</label>
                    <input type="text" name="option_a" id="option_a" class="form-control" value="{{ old('option_a', $question->option_a) }}">
                </div>

                <div class="mb-3">
                    <label for="option_b" class="form-label">Option B</label>
                    <input type="text" name="option_b" id="option_b" class="form-control" value="{{ old('option_b', $question->option_b) }}">
                </div>

                <div class="mb-3">
                    <label for="option_c" class="form-label">Option C</label>
                    <input type="text" name="option_c" id="option_c" class="form-control" value="{{ old('option_c', $question->option_c) }}">
                </div>

                <div class="mb-3">
                    <label for="option_d" class="form-label">Option D</label>
                    <input type="text" name="option_d" id="option_d" class="form-control" value="{{ old('option_d', $question->option_d) }}">
                </div>

                <div class="mb-3">
                    <label for="correct_answer" class="form-label">Correct Answer</label>
                    <select name="correct_answer" id="correct_answer" class="form-select">
                        <option value="A" {{ old('correct_answer', $question->correct_answer) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('correct_answer', $question->correct_answer) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('correct_answer', $question->correct_answer) == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('correct_answer', $question->correct_answer) == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="explanation" class="form-label">Explanation</label>
                    <textarea name="explanation" id="explanation" rows="3" class="form-control">{{ old('explanation', $question->explanation) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection