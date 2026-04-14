@extends('layouts.app')

@section('content')
    <div class="content-wrap">
        <section class="card mb-4 overflow-hidden">
            <div class="card-body p-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="badge-role mb-3 d-inline-block">AI-Powered English Learning</span>

                        <h1 class="display-5 fw-bold mb-3">
                            Improve your English grammar with
                            <span style="color: var(--primary);">instant feedback</span>
                        </h1>

                        <p class="lead text-muted mb-4">
                            Practice grammar, check sentences with AI, take quizzes by topic,
                            and track your learning progress in one simple platform.
                        </p>

                        <div class="d-flex flex-wrap gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                    Go to Dashboard
                                </a>
                                <a href="{{ route('grammar.check') }}" class="btn btn-outline-primary btn-lg">
                                    Try Grammar Checker
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                    Get Started
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                    Sign In
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="soft-panel h-100">
                            <h5 class="mb-3">What you can do</h5>

                            <div class="d-flex flex-column gap-3">
                                <div class="p-3 rounded-3" style="background: var(--surface-2); border: 1px solid var(--border);">
                                    <h6 class="mb-1">AI Grammar Checker</h6>
                                    <p class="text-muted mb-0 small">
                                        Get corrected sentences and simple explanations instantly.
                                    </p>
                                </div>

                                <div class="p-3 rounded-3" style="background: var(--surface-2); border: 1px solid var(--border);">
                                    <h6 class="mb-1">Topic-Based Quizzes</h6>
                                    <p class="text-muted mb-0 small">
                                        Learn through categories and topics like tenses, prepositions, and adjectives.
                                    </p>
                                </div>

                                <div class="p-3 rounded-3" style="background: var(--surface-2); border: 1px solid var(--border);">
                                    <h6 class="mb-1">Progress Tracking</h6>
                                    <p class="text-muted mb-0 small">
                                        Review your grammar history and quiz attempts anytime.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3" style="font-size: 1.6rem;">📝</div>
                            <h4 class="mb-2">Check Grammar</h4>
                            <p class="text-muted mb-0">
                                Enter a sentence or paragraph and receive AI-powered correction with explanation.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3" style="font-size: 1.6rem;">📚</div>
                            <h4 class="mb-2">Practice by Topic</h4>
                            <p class="text-muted mb-0">
                                Choose a category, select a topic, and test your understanding with quiz questions.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3" style="font-size: 1.6rem;">📈</div>
                            <h4 class="mb-2">Track Progress</h4>
                            <p class="text-muted mb-0">
                                Monitor grammar checks, quiz attempts, and your overall improvement over time.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="card">
            <div class="card-body p-4 p-lg-5 text-center">
                <h3 class="mb-3">Start improving your grammar today</h3>
                <p class="text-muted mb-4">
                    Build confidence in writing through AI support and structured grammar practice.
                </p>

                @auth
                    <a href="{{ route('quiz.categories') }}" class="btn btn-primary btn-lg">
                        Start a Quiz
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        Create Free Account
                    </a>
                @endauth
            </div>
        </section>
    </div>
@endsection