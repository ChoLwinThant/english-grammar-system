@extends('layouts.app')

@section('content')
    <style>
        .home-page {
            display: flex;
            flex-direction: column;
            gap: 3.2rem;
        }

        .hero-section {
            position: relative;
            overflow: hidden;
            border-radius: 0;
            min-height: 560px;
            padding: 6rem 2.2rem 4.8rem;
            display: flex;
            align-items: center;
            background-image: url("https://images.unsplash.com/photo-1516397281156-ca07cf9746fc?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
            background-size: cover;
            background-position: center 35%;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            margin-top: -3rem;
        }

        .hero-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(95deg, rgba(248, 243, 255, 0.82) 0%, rgba(241, 233, 255, 0.76) 45%, rgba(228, 244, 255, 0.7) 100%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.2) 100%);
            pointer-events: none;
            z-index: 0;
        }

        .hero-section::after {
            content: "";
            position: absolute;
            left: -5%;
            right: -5%;
            bottom: -44px;
            height: 110px;
            background: var(--bg);
            border-top-left-radius: 60% 100%;
            border-top-right-radius: 60% 100%;
            z-index: 0;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 1240px;
            width: 100%;
            text-shadow: none;
            margin-left: clamp(0.45rem, 1.6vw, 1.4rem);
        }

        .hero-title {
            font-size: clamp(2rem, 3.9vw, 3.05rem);
            line-height: 1.08;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            max-width: 760px;
            color: #2e1065;
        }

        .hero-copy {
            font-size: 1.05rem;
            line-height: 1.78;
            color: #3f3f5b;
            max-width: 640px;
        }

        .hero-actions {
            margin-top: 1.35rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .hero-actions-guest {
            width: auto;
            justify-content: flex-start;
            align-items: center;
        }

        .hero-actions-guest .btn {
            padding: 0.62rem 1.05rem;
            font-size: 1.05rem;
            border-radius: 11px;
        }

        .hero-meta {
            margin-top: 1.3rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            color: #4c3f72;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .hero-section .badge-role {
            background-color: rgba(109, 40, 217, 0.13);
            border-color: rgba(109, 40, 217, 0.3);
            color: #4c1d95;
        }

        .hero-section .btn-outline-primary {
            color: var(--primary);
            border-color: rgba(109, 40, 217, 0.4);
            background-color: rgba(255, 255, 255, 0.55);
        }

        .hero-section .btn-outline-primary:hover {
            color: var(--primary);
            background-color: #ffffff;
            border-color: var(--primary);
        }

        .section-block {
            position: relative;
            padding-top: 1.5rem;
        }

        .section-block::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 72px;
            height: 2px;
            background: #d1d5db;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 0.45rem;
        }

        .section-copy {
            color: var(--text-muted);
            margin-bottom: 1.15rem;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.4rem;
        }

        .feature-item {
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--primary-soft);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 38px;
        }

        .feature-item h5 {
            margin-bottom: 0.3rem;
        }

        .feature-item p {
            margin: 0;
            font-size: 0.94rem;
            color: var(--text-muted);
            line-height: 1.62;
        }

        .steps-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 1.6rem;
            align-items: start;
        }

        .roadmap-steps {
            margin-top: 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .roadmap-step-card {
            display: flex;
            gap: 0.85rem;
            padding: 0.9rem 1rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: linear-gradient(160deg, #ffffff 0%, #faf7ff 100%);
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.05);
        }

        .step-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #d8ccff;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 34px;
            z-index: 1;
            box-shadow: 0 4px 10px rgba(109, 40, 217, 0.15);
        }

        .step-num {
            color: var(--primary);
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 0.25rem;
        }

        .roadmap-step-card p {
            margin: 0.2rem 0 0;
            color: var(--text-muted);
            font-size: 0.93rem;
            line-height: 1.6;
        }

        .steps-photo {
            width: 100%;
            border-radius: 14px;
            object-fit: cover;
            aspect-ratio: 4 / 3;
        }

        .cta-section {
            border-top: 1px solid #e5e7eb;
            padding-top: 2rem;
            padding-bottom: 0.75rem;
            text-align: center;
        }

        .cta-section p {
            color: var(--text-muted);
            max-width: 680px;
            margin: 0.7rem auto 1.4rem;
        }

        .navbar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background: rgba(255, 255, 255, 0.5) !important;
            border-bottom: 1px solid rgba(109, 40, 217, 0.15) !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05) !important;
            backdrop-filter: blur(8px);
        }

        .navbar-brand,
        .navbar .nav-link,
        .navbar .nav-link.active-nav {
            color: #4c1d95 !important;
        }

        .navbar .nav-link.active-nav {
            border-bottom-color: rgba(109, 40, 217, 0.55);
        }

        .navbar .nav-link:hover {
            color: #5b21b6 !important;
        }

        @media (max-width: 991.98px) {
            .feature-list {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .steps-layout {
                grid-template-columns: 1fr;
            }

            .hero-section {
                min-height: 400px;
                padding: 5.5rem 1.3rem 3.8rem;
            }

            .hero-actions-guest {
                width: auto;
                justify-content: flex-start;
            }

            .hero-actions-guest .btn {
                width: auto;
                font-size: 1rem;
            }
        }
    </style>

    <div class="content-wrap home-page">
        <section class="hero-section">
            <div class="hero-content">
                <span class="badge-role mb-3 d-inline-block">AI-Powered English Training</span>
                <h1 class="hero-title fw-bold">Improve grammar with real-time correction and focused practice</h1>
                <p class="hero-copy mb-0">
                    Improve writing quality through instant feedback, topic-based quizzes, and progress history designed for consistent daily learning.
                </p>

                @auth
                    <div class="hero-actions">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                        <a href="{{ route('grammar.check') }}" class="btn btn-outline-primary btn-lg">Try Grammar Checker</a>
                    </div>
                @else
                    <div class="hero-actions hero-actions-guest">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Create Free Account</a>
                    </div>
                @endauth

                <div class="hero-meta">
                    <span>Fast AI feedback</span>
                    <span>Quiz by grammar topics</span>
                    <span>Track learning progress</span>
                </div>
            </div>
        </section>

        <section class="section-block">
            <h2 class="section-title">Built for practical daily improvement</h2>
            <p class="section-copy">No heavy card layout, just clear features and clean reading flow.</p>

            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                    </span>
                    <div>
                        <h5 class="h6">Grammar Checker</h5>
                        <p>Correct sentence errors and understand each change through simple explanations.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <span class="feature-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5V4.5A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </span>
                    <div>
                        <h5 class="h6">Quiz by Topic</h5>
                        <p>Practice grammar areas like tenses and prepositions through focused quizzes.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <span class="feature-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3v18h18"></path>
                            <path d="M18 9l-5 5-3-3-4 4"></path>
                        </svg>
                    </span>
                    <div>
                        <h5 class="h6">Track Progress</h5>
                        <p>Review your check history and quiz results to see improvement over time.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="steps-layout">
                <div>
                    <div>
                        <h2 class="section-title">How it works</h2>
                        <p class="section-copy">Simple process, clear results.</p>
                    </div>
                    <div class="roadmap-steps">
                        <div class="roadmap-step-card">
                            <span class="step-icon" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 11l3 3L22 4"></path>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                            </span>
                            <div>
                                <span class="step-num">Step 1</span>
                                <h5 class="h6 mb-1">Check your writing</h5>
                                <p>Paste a sentence and review instant AI corrections.</p>
                            </div>
                        </div>
                        <div class="roadmap-step-card">
                            <span class="step-icon" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5V4.5A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </span>
                            <div>
                                <span class="step-num">Step 2</span>
                                <h5 class="h6 mb-1">Practice weak points</h5>
                                <p>Choose quiz topics based on where errors happen most.</p>
                            </div>
                        </div>
                        <div class="roadmap-step-card">
                            <span class="step-icon" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M18 9l-5 5-3-3-4 4"></path>
                                </svg>
                            </span>
                            <div>
                                <span class="step-num">Step 3</span>
                                <h5 class="h6 mb-1">Measure progress</h5>
                                <p>Use your history to keep improving with a structured routine.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <img
                    class="steps-photo"
                    src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=1200&q=80"
                    alt="Student learning grammar with notes and laptop"
                    loading="lazy"
                >
            </div>
        </section>

        <section class="cta-section">
            <h3 class="mb-2">Start improving your English grammar today</h3>
            <p>Learn from each correction, practice intentionally, and build better writing confidence.</p>
            @auth
                <a href="{{ route('quiz.categories') }}" class="btn btn-primary btn-lg">Start a Quiz</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Free Account</a>
            @endauth
        </section>
    </div>
@endsection
