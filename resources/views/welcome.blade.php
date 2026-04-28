<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'English Grammar System') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6d28d9;
            --primary-hover: #5b21b6;
            --primary-soft: #f5f3ff;
            --primary-border: #ddd6fe;
            --bg: #f7f8fc;
            --surface: #ffffff;
            --text: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(109, 40, 217, 0.08), transparent 30%),
                linear-gradient(180deg, #fcfbff 0%, var(--bg) 100%);
            font-family: 'Aptos', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: var(--text);
        }

        .hero-title,
        .section-title,
        .feature-title {
            font-family: Cambria, Georgia, 'Times New Roman', Times, serif;
        }

        .site-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
        }

        .brand-mark {
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
            color: #fff;
            font-weight: 700;
        }

        .hero-shell {
            padding: 4.5rem 0 5rem;
        }

        .hero-card {
            border: 1px solid var(--border);
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .hero-copy {
            padding: 3rem;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            border: 1px solid var(--primary-border);
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.2rem);
            line-height: 1.02;
            letter-spacing: -0.04em;
            margin-bottom: 1rem;
        }

        .hero-text {
            color: var(--text-muted);
            font-size: 1.08rem;
            line-height: 1.8;
            max-width: 54ch;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem;
            margin-top: 2rem;
        }

        .btn-main {
            border-radius: 12px;
            padding: 0.85rem 1.2rem;
            font-weight: 600;
        }

        .btn-primary-custom {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-primary-custom:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
        }

        .btn-soft {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text);
        }

        .btn-soft:hover {
            background: #f9fafb;
            color: var(--text);
        }

        .hero-panel {
            height: 100%;
            padding: 3rem;
            background:
                linear-gradient(180deg, rgba(109, 40, 217, 0.08) 0%, rgba(79, 70, 229, 0.03) 100%),
                #fafaff;
            border-left: 1px solid var(--border);
        }

        .hero-stat {
            padding: 1.2rem 1.25rem;
            border: 1px solid rgba(109, 40, 217, 0.12);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.82);
            margin-bottom: 1rem;
        }

        .hero-stat-label {
            margin: 0 0 0.35rem;
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .hero-stat-value {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .section-copy {
            color: var(--text-muted);
            max-width: 60ch;
            line-height: 1.8;
        }

        .feature-card {
            height: 100%;
            padding: 1.6rem;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: var(--surface);
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.05);
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.3rem;
            margin-bottom: 0.65rem;
        }

        .feature-text {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.75;
        }

        @media (max-width: 991.98px) {
            .hero-copy,
            .hero-panel {
                padding: 2rem;
            }

            .hero-panel {
                border-left: 0;
                border-top: 1px solid var(--border);
            }

            .hero-shell {
                padding-top: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg site-nav">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center gap-3 fw-semibold text-dark" href="{{ url('/') }}">
                <span class="brand-mark">EG</span>
                <span>English Grammar System</span>
            </a>

            @if (Route::has('login'))
                <div class="d-flex align-items-center gap-2 ms-auto">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-soft btn-main">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-soft btn-main">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary-custom btn-main">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <main class="hero-shell">
        <div class="container">
            <section class="hero-card">
                <div class="row g-0">
                    <div class="col-lg-7">
                        <div class="hero-copy">
                            <div class="hero-kicker">Professional English Practice</div>
                            <h1 class="hero-title">Build confident grammar with a cleaner, more focused learning experience.</h1>
                            <p class="hero-text">
                                Practice grammar topics, check sentence quality, and follow your learning progress in one place designed to feel structured, calm, and credible.
                            </p>

                            <div class="hero-actions">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary-custom btn-main">Open Dashboard</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary-custom btn-main">Start Learning</a>
                                    <a href="{{ route('login') }}" class="btn btn-soft btn-main">Sign In</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="hero-panel">
                            <div class="hero-stat">
                                <p class="hero-stat-label">Grammar Practice</p>
                                <p class="hero-stat-value">Guided quizzes</p>
                            </div>
                            <div class="hero-stat">
                                <p class="hero-stat-label">Sentence Review</p>
                                <p class="hero-stat-value">Clear feedback</p>
                            </div>
                            <div class="hero-stat mb-0">
                                <p class="hero-stat-label">Learning Flow</p>
                                <p class="hero-stat-value">Progress tracking</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="pt-5 mt-4">
                <h2 class="section-title">A more realistic academic tone</h2>
                <p class="section-copy mb-4">
                    The interface now favors professional typography and calmer visual structure, which makes grammar content feel more trustworthy and easier to read for longer study sessions.
                </p>

                <div class="row g-4">
                    <div class="col-md-4">
                        <article class="feature-card">
                            <div class="feature-icon">A</div>
                            <h3 class="feature-title">Professional Typography</h3>
                            <p class="feature-text">Body text stays modern and legible, while headings use a formal serif tone that feels more academic.</p>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="feature-card">
                            <div class="feature-icon">B</div>
                            <h3 class="feature-title">Focused Practice</h3>
                            <p class="feature-text">Move from grammar checking to topic-based quizzes without visual clutter pulling attention away from the task.</p>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="feature-card">
                            <div class="feature-icon">C</div>
                            <h3 class="feature-title">Credible Presentation</h3>
                            <p class="feature-text">The updated landing page removes the old generic template and presents the system as a real learning product.</p>
                        </article>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
