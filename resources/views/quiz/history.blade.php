@extends('layouts.app')

@section('content')
    <style>
        .quiz-history-head {
            margin-bottom: 1.5rem;
        }

        .quiz-history-topic-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.65rem;
        }

        .quiz-history-topic-row h5 {
            margin: 0;
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

    <div class="quiz-history-head">
        <h2 class="mb-0">Quiz History</h2>
    </div>

    @if($attempts->isEmpty())
        <div class="alert alert-info">No quiz attempts found.</div>
    @else
        @foreach($attempts as $attempt)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="quiz-history-topic-row">
                        <h5>{{ $attempt->topic->name }}</h5>
                        <span class="quiz-difficulty-badge quiz-difficulty-badge-{{ $attempt->topic->difficulty }}">
                            {{ $attempt->topic->difficultyLabel() }}
                        </span>
                    </div>
                    <p><strong>Category:</strong> {{ $attempt->topic->category->name }}</p>
                    <p><strong>Score:</strong> {{ $attempt->score }} / {{ $attempt->total_questions }}</p>
                    <small class="text-muted">
                        Attempted on {{ $attempt->created_at->format('d M Y, h:i A') }}
                    </small>
                </div>
            </div>
        @endforeach
    @endif
@endsection
