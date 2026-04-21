@extends('layouts.app')

@section('content')
    <style>
        .quiz-page {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .quiz-head {
            border-bottom: 1px solid var(--border);
            padding-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .quiz-kicker {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.72rem;
            border-radius: 999px;
            border: 1px solid var(--primary-border);
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .quiz-title {
            margin-top: 0.8rem;
            margin-bottom: 0.4rem;
            font-size: clamp(1.5rem, 2.6vw, 2rem);
        }

        .quiz-subtitle {
            color: var(--text-muted);
            margin: 0;
            max-width: 760px;
        }

        .quiz-list {
            border-top: 1px solid var(--border);
        }

        .quiz-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0.3rem;
            border-bottom: 1px solid var(--border);
            transition: background-color 0.16s ease;
        }

        .quiz-item:hover {
            background: #fafafa;
        }

        .quiz-item h5 {
            margin: 0 0 0.25rem;
        }

        .quiz-item p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.92rem;
        }

        .quiz-empty {
            border: 1px dashed var(--border);
            border-radius: 12px;
            background: #fff;
            padding: 1rem;
            color: var(--text-muted);
        }

        .quiz-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.48rem 0.9rem;
            border-radius: 999px;
            border: 1px solid var(--primary-border);
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color 0.16s ease, border-color 0.16s ease, color 0.16s ease,
                transform 0.16s ease;
        }

        .quiz-action:hover {
            background: #e8f0ff;
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
        }

        @media (max-width: 767.98px) {
            .quiz-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="quiz-page">
        <section class="quiz-head">
            <div>
                <span class="quiz-kicker">Quiz Topics</span>
                <h2 class="quiz-title">Topics in {{ $category->name }}</h2>
                <p class="quiz-subtitle">Select a topic and start a focused quiz to strengthen this grammar area.</p>
            </div>
            <a href="{{ route('quiz.categories') }}" class="btn btn-outline-primary">Back to Categories</a>
        </section>

        @if($topics->isEmpty())
            <div class="quiz-empty">No topics available in this category yet.</div>
        @else
            <section class="quiz-list">
                @foreach($topics as $topic)
                    <article class="quiz-item">
                        <div>
                            <h5>{{ $topic->name }}</h5>
                            <p>{{ $topic->description ?: 'Start this topic quiz to test understanding and improve accuracy.' }}</p>
                        </div>
                        <a href="{{ route('quiz.start', $topic) }}" class="quiz-action">Start Quiz</a>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
