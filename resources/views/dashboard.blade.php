@extends('layouts.app')

@section('content')
    <div class="content-wrap">
        <div class="card shadow-soft mb-4">
        <div class="card-body p-4">
            <h2 class="section-title mb-2">Welcome back, {{ Auth::user()->name }} 👋</h2>
            <p class="text-muted mb-0">
                Here is a quick overview of your learning activity.
            </p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body p-4">
                    <h5 class="text-muted mb-2">Total Grammar Checks</h5>
                    <h2 class="fw-bold mb-0">{{ $totalGrammarChecks }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body p-4">
                    <h5 class="text-muted mb-2">Total Quiz Attempts</h5>
                    <h2 class="fw-bold mb-0">{{ $totalQuizAttempts }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body p-4">
                    <h5 class="text-muted mb-2">Average Quiz Score</h5>
                    <h2 class="fw-bold mb-0">
                        {{ $totalQuizAttempts > 0 ? number_format($averageQuizScore, 1) : 0 }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-soft h-100">
                <div class="card-body p-4">
                    <h4 class="section-title">Latest Grammar Check</h4>

                    @if($latestGrammarCheck)
                        <p><strong>Original:</strong> {{ $latestGrammarCheck->original_text }}</p>
                        <p><strong>Corrected:</strong> {{ $latestGrammarCheck->corrected_text }}</p>
                        <small class="text-muted">
                            {{ $latestGrammarCheck->created_at->format('d M Y, h:i A') }}
                        </small>
                    @else
                        <p class="text-muted mb-0">No grammar checks yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-soft h-100">
                <div class="card-body p-4">
                    <h4 class="section-title">Latest Quiz Attempt</h4>

                    @if($latestQuizAttempt)
                        <p><strong>Category:</strong> {{ $latestQuizAttempt->topic->category->name }}</p>
                        <p><strong>Topic:</strong> {{ $latestQuizAttempt->topic->name }}</p>
                        <p><strong>Score:</strong> {{ $latestQuizAttempt->score }} / {{ $latestQuizAttempt->total_questions }}</p>
                        <small class="text-muted">
                            {{ $latestQuizAttempt->created_at->format('d M Y, h:i A') }}
                        </small>
                    @else
                        <p class="text-muted mb-0">No quiz attempts yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-soft">
        <div class="card-body p-4">
            <h4 class="section-title">Quick Actions</h4>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('grammar.check') }}" class="btn btn-primary">📝 Grammar Checker</a>
                <a href="{{ route('grammar.history') }}" class="btn btn-outline-primary">📚 Grammar History</a>
                <a href="{{ route('quiz.categories') }}" class="btn btn-primary">🧠 Start Quiz</a>
                <a href="{{ route('quiz.history') }}" class="btn btn-outline-primary">📊 Quiz History</a>
            </div>
        </div>
    </div>
    </div>
@endsection