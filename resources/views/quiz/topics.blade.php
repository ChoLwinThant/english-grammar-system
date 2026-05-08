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

        .quiz-filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.1rem;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff 0%, #fafbff 100%);
        }

        .quiz-filter-label {
            margin: 0;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .quiz-filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }

        .quiz-filter-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.38rem 0.7rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text-muted);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-decoration: none;
            text-transform: uppercase;
            transition: border-color 0.16s ease, background-color 0.16s ease, color 0.16s ease;
        }

        .quiz-filter-pill:hover {
            border-color: var(--primary-border);
            background: var(--primary-soft);
            color: var(--primary);
        }

        .quiz-filter-pill.active {
            border-color: var(--primary-border);
            background: var(--primary-soft);
            color: var(--primary);
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

        .quiz-topic-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.65rem;
            margin-bottom: 0.25rem;
        }

        .quiz-topic-title {
            margin: 0;
        }

        .quiz-difficulty {
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

        .quiz-difficulty-basic {
            background: #ecfdf3;
            color: #166534;
            border-color: #86efac;
        }

        .quiz-difficulty-elementary {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #93c5fd;
        }

        .quiz-difficulty-intermediate {
            background: #fff7ed;
            color: #c2410c;
            border-color: #fdba74;
        }

        .quiz-difficulty-advanced {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fca5a5;
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

        @if($hasTopics)
            <section class="quiz-filter-bar">
                
                <div class="quiz-filter-actions">
                    <a
                        href="{{ route('quiz.topics', $category) }}"
                        class="quiz-filter-pill {{ $selectedDifficulty === '' ? 'active' : '' }}"
                    >
                        All
                    </a>
                    @foreach($difficultyOptions as $value => $label)
                        <a
                            href="{{ route('quiz.topics', ['category' => $category, 'difficulty' => $value]) }}"
                            class="quiz-filter-pill {{ $selectedDifficulty === $value ? 'active' : '' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if($topics->isEmpty())
            <div class="quiz-empty">
                {{ $selectedDifficulty !== ''
                    ? 'No topics match this difficulty in the selected category yet.'
                    : 'No topics available in this category yet.' }}
            </div>
        @else
            <section class="quiz-list">
                @foreach($topics as $topic)
                    <article class="quiz-item">
                        <div>
                            <div class="quiz-topic-head">
                                <h5 class="quiz-topic-title">{{ $topic->name }}</h5>
                                <span class="quiz-difficulty quiz-difficulty-{{ $topic->difficulty }}">
                                    {{ $topic->difficultyLabel() }}
                                </span>
                            </div>
                            <p>{{ $topic->description ?: 'Start this topic quiz to test understanding and improve accuracy.' }}</p>
                        </div>
                        <a href="{{ route('quiz.start', $topic) }}" class="quiz-action">Start Quiz</a>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
