@extends('layouts.app')

@section('content')
    <style>
        .quiz-topic-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.65rem;
            margin-bottom: 0.85rem;
        }

        .quiz-difficulty-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.22rem 0.56rem;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 1px solid transparent;
            line-height: 1.1;
        }

        .quiz-difficulty-badge-basic {
            background: #ecfdf3;
            color: #166534;
            border-color: #86efac;
        }

        .quiz-difficulty-badge-elementary {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #93c5fd;
        }

        .quiz-difficulty-badge-intermediate {
            background: #fff7ed;
            color: #c2410c;
            border-color: #fdba74;
        }

        .quiz-difficulty-badge-advanced {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fca5a5;
        }
    </style>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="mb-3">Quiz Result</h2>

            <div class="quiz-topic-meta">
                <p class="mb-0"><strong>Topic:</strong> {{ $topic->name }}</p>
                <span class="quiz-difficulty-badge quiz-difficulty-badge-{{ $topic->difficulty }}">
                    {{ $topic->difficultyLabel() }}
                </span>
            </div>
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
