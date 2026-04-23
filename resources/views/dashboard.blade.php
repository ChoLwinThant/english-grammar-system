@extends('layouts.app')

@section('content')
    <div class="content-wrap dashboard-shell">
        <section class="dashboard-hero">
            <div class="row g-4 align-items-end">
                <div class="col-lg-7">
                    <div class="dashboard-hero-content">
                        <span class="dashboard-kicker">Personal Dashboard</span>
                        <h1 class="dashboard-hero-title">Welcome back, {{ Auth::user()->name }}</h1>
                        <p class="dashboard-hero-copy">
                            Your learning space now puts the latest work front and center, with quick access to practice,
                            review, and grammar improvements.
                        </p>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="dashboard-hero-actions">
                        <div class="dashboard-stats">
                            <article class="dashboard-stat">
                                <p class="dashboard-stat-label">Grammar Checks</p>
                                <p class="dashboard-stat-value">{{ $totalGrammarChecks }}</p>
                                <p class="dashboard-stat-note">Saved corrections you can revisit anytime.</p>
                            </article>

                            <article class="dashboard-stat">
                                <p class="dashboard-stat-label">Quiz Attempts</p>
                                <p class="dashboard-stat-value">{{ $totalQuizAttempts }}</p>
                                <p class="dashboard-stat-note">Practice runs completed across your topics.</p>
                            </article>

                            <article class="dashboard-stat">
                                <p class="dashboard-stat-label">Average Score</p>
                                <p class="dashboard-stat-value">{{ $totalQuizAttempts > 0 ? number_format($averageQuizScore, 1) : 0 }}</p>
                                <p class="dashboard-stat-note">Average result from your recorded quizzes.</p>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="dashboard-body">
            <div class="dashboard-stack">
                <section class="dashboard-panel">
                    <div class="dashboard-panel-header">
                        <div>
                            <p class="dashboard-eyebrow">Latest Writing Review</p>
                            <h2 class="dashboard-panel-title">Grammar check snapshot</h2>
                            <p class="dashboard-panel-copy">See your most recent draft and the corrected version side by side.</p>
                        </div>
                    </div>

                    @if($latestGrammarCheck)
                        <div class="dashboard-highlight">
                            <div class="dashboard-highlight-block">
                                <p class="dashboard-highlight-label">Original Text</p>
                                <p class="dashboard-highlight-text">{{ $latestGrammarCheck->original_text }}</p>
                            </div>

                            <div class="dashboard-highlight-block">
                                <p class="dashboard-highlight-label">Corrected Text</p>
                                <p class="dashboard-highlight-text">{{ $latestGrammarCheck->corrected_text }}</p>
                            </div>

                            <p class="dashboard-meta">
                                Checked on {{ $latestGrammarCheck->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    @else
                        <div class="dashboard-empty">
                            You have not run a grammar check yet. Start with a short paragraph and your first correction
                            will appear here.
                        </div>
                    @endif
                </section>
            </div>

            <div class="dashboard-stack">
                <section class="dashboard-panel">
                    <div class="dashboard-panel-header">
                        <div>
                            <p class="dashboard-eyebrow">Latest Quiz</p>
                            <h2 class="dashboard-panel-title">Practice progress</h2>
                            <p class="dashboard-panel-copy">Your newest quiz result stays visible so it is easier to keep momentum.</p>
                        </div>
                    </div>

                    @if($latestQuizAttempt)
                        <div class="dashboard-highlight">
                            <div class="dashboard-highlight-block">
                                <p class="dashboard-highlight-label">Category</p>
                                <p class="dashboard-highlight-text">{{ $latestQuizAttempt->topic->category->name }}</p>
                            </div>

                            <div class="dashboard-highlight-block">
                                <p class="dashboard-highlight-label">Topic</p>
                                <p class="dashboard-highlight-text">{{ $latestQuizAttempt->topic->name }}</p>
                            </div>

                            <div class="dashboard-highlight-block">
                                <p class="dashboard-highlight-label">Score</p>
                                <p class="dashboard-highlight-text">
                                    {{ $latestQuizAttempt->score }} / {{ $latestQuizAttempt->total_questions }}
                                </p>
                            </div>

                            <p class="dashboard-meta">
                                Attempted on {{ $latestQuizAttempt->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    @else
                        <div class="dashboard-empty">
                            No quiz attempts yet. Pick a category and start a short practice round to begin tracking progress.
                        </div>
                    @endif
                </section>

                <div class="dashboard-fab-backdrop" id="dashboardFabBackdrop" hidden></div>
                <section class="dashboard-fab" id="dashboardFab" aria-label="Quick actions">
                    <div class="dashboard-fab-menu" id="dashboardFabMenu">
                        <a href="{{ route('grammar.check') }}" class="dashboard-tool">
                            <span class="dashboard-tool-icon" aria-hidden="true"><i class="bi bi-pencil-square"></i></span>
                            <span class="dashboard-tool-body">
                                <span class="dashboard-tool-title d-block">Grammar Checker</span>
                                <span class="dashboard-tool-copy d-block">Fix a new sentence</span>
                            </span>
                        </a>

                        <a href="{{ route('grammar.history') }}" class="dashboard-tool">
                            <span class="dashboard-tool-icon" aria-hidden="true"><i class="bi bi-clock-history"></i></span>
                            <span class="dashboard-tool-body">
                                <span class="dashboard-tool-title d-block">Grammar History</span>
                                <span class="dashboard-tool-copy d-block">Review past fixes</span>
                            </span>
                        </a>

                        <a href="{{ route('quiz.categories') }}" class="dashboard-tool">
                            <span class="dashboard-tool-icon" aria-hidden="true"><i class="bi bi-patch-question"></i></span>
                            <span class="dashboard-tool-body">
                                <span class="dashboard-tool-title d-block">Start Quiz</span>
                                <span class="dashboard-tool-copy d-block">Jump into practice</span>
                            </span>
                        </a>

                        <a href="{{ route('quiz.history') }}" class="dashboard-tool">
                            <span class="dashboard-tool-icon" aria-hidden="true"><i class="bi bi-bar-chart-line"></i></span>
                            <span class="dashboard-tool-body">
                                <span class="dashboard-tool-title d-block">Quiz History</span>
                                <span class="dashboard-tool-copy d-block">Track your scores</span>
                            </span>
                        </a>
                    </div>

                    <button
                        type="button"
                        class="dashboard-fab-toggle"
                        id="dashboardFabToggle"
                        aria-label="Open quick actions"
                        aria-expanded="false"
                        aria-controls="dashboardFabMenu"
                    >
                        <span class="dashboard-fab-toggle-text" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
                    </button>
                </section>
            </div>
        </div>
    </div>
@endsection
