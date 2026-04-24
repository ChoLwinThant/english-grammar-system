@extends('layouts.app')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="mb-3">Quiz Result</h2>

            <p><strong>Topic:</strong> {{ $topic->name }}</p>
            <p><strong>Your Score:</strong> {{ $score }} / {{ $questions->count() }}</p>

            <a href="{{ route('quiz.categories') }}" class="btn btn-primary me-2">Back to Categories</a>
            <a href="{{ route('quiz.history') }}" class="btn btn-outline-primary">View Quiz History</a>
        </div>
    </div>

    <h3 class="mb-3">Answer Review</h3>

    @foreach($results as $index => $result)
        <div class="card shadow-sm mb-3 border-{{ $result['is_correct'] ? 'success' : 'danger' }}">
            <div class="card-body">
                <h5>Question {{ $index + 1 }}</h5>
                <p><strong>{{ $result['question_text'] }}</strong></p>

                <p>
                    <strong>Your Answer:</strong>
                    <span class="{{ $result['is_correct'] ? 'text-success' : 'text-danger' }}">
                        {{ $result['selected_answer'] ? $result['selected_answer_text'] : 'No answer' }}
                    </span>
                </p>

                <p>
                    <strong>Correct Answer:</strong>
                    <span class="text-success">
                        {{ $result['correct_answer_text'] }}
                    </span>
                </p>

                <p>
                    <strong>Result:</strong>
                    @if($result['is_correct'])
                        <span class="badge bg-success">Correct</span>
                    @else
                        <span class="badge bg-danger">Wrong</span>
                    @endif
                </p>

                @if($result['explanation'])
                    <div class="alert alert-info border mt-3 mb-0">
                        <strong>Explanation:</strong><br>
                        {{ $result['explanation'] }}
                    </div>
                    {{-- <div class="alert alert-info mt-2 mb-0">
                        <strong>Explanation:</strong><br>
                        {{ $result['explanation'] }}
                    </div> --}}
                @endif
            </div>
        </div>
    @endforeach
@endsection
