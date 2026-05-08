@extends('layouts.app')

@section('content')
    <style>
        .quiz-start-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .quiz-start-title-wrap {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.7rem;
        }

        .quiz-start-title {
            margin: 0;
        }

        .quiz-start-subtitle {
            margin: 0.45rem 0 0;
            color: var(--text-muted);
        }

        .quiz-start-badge {
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

        .quiz-start-badge-basic {
            background: #ecfdf3;
            color: #166534;
            border-color: #86efac;
        }

        .quiz-start-badge-elementary {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #93c5fd;
        }

        .quiz-start-badge-intermediate {
            background: #fff7ed;
            color: #c2410c;
            border-color: #fdba74;
        }

        .quiz-start-badge-advanced {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fca5a5;
        }
    </style>

    <div class="quiz-start-head">
        <div>
            <div class="quiz-start-title-wrap">
                <h2 class="quiz-start-title">{{ $topic->name }} Quiz</h2>
                <span class="quiz-start-badge quiz-start-badge-{{ $topic->difficulty }}">
                    {{ $topic->difficultyLabel() }}
                </span>
            </div>
            <p class="quiz-start-subtitle">
                {{ $topic->description ?: 'Answer the questions below to check your understanding of this grammar topic.' }}
            </p>
        </div>
        <a href="{{ route('quiz.topics', $topic->category) }}" class="btn btn-outline-primary">Back to Topics</a>
    </div>

    @if($questions->isEmpty())
        <div class="alert alert-info">No questions available for this topic.</div>
    @else
        <form method="POST" action="{{ route('quiz.submit', $topic) }}">
            @csrf

            @foreach($questions as $index => $question)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Question {{ $index + 1 }}</h5>
                        <p><strong>{{ $question->question_text }}</strong></p>

                        @foreach($question->display_options as $option)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="question_{{ $question->id }}"
                                    value="{{ $option['key'] }}"
                                    id="q{{ $question->id }}{{ strtolower($option['key']) }}"
                                >
                                <label class="form-check-label" for="q{{ $question->id }}{{ strtolower($option['key']) }}">
                                    {{ $option['text'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </form>
    @endif
@endsection
