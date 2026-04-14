<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'English Grammar System') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
    :root {
        --primary: #6d28d9;
        --primary-hover: #5b21b6;
        --primary-soft: #f5f3ff;
        --primary-border: #ddd6fe;

        --bg: #f8fafc;
        --surface: #ffffff;
        --surface-2: #f9fafb;

        --text: #111827;
        --text-muted: #6b7280;

        --border: #e5e7eb;
        --success: #16a34a;
        --danger: #dc2626;
    }

    body {
        background-color: var(--bg);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text);
    }

    .container {
        max-width: 1320px;
    }

    .navbar {
        background: #ffffff !important;
        border-bottom: 1px solid var(--border);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .navbar-brand {
        color: var(--primary) !important;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .nav-link {
    color: var(--text-muted) !important;
    font-weight: 500;
    transition: color 0.2s ease;
    border-bottom: 2px solid transparent;
    padding-bottom: 0.4rem;
}

.nav-link:hover {
    color: var(--primary) !important;
}

.nav-link.active-nav {
    color: var(--primary) !important;
    font-weight: 700;
    border-bottom: 2px solid var(--primary);
}

    .card {
        border: 1px solid var(--border);
        border-radius: 16px;
        background-color: var(--surface);
        box-shadow: 0 8px 24px rgba(17, 24, 39, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(17, 24, 39, 0.06);
    }

    .card-body {
        padding: 1.5rem;
    }

    h1, h2, h3, h4, h5,
    .page-title,
    .section-title,
    .card-title {
        color: var(--text);
        font-weight: 700;
    }

    .section-title {
        margin-bottom: 1rem;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.65rem 1rem;
    }

    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary-border);
        background-color: #fff;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-soft);
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-warning,
    .btn-danger,
    .btn-success {
        border-radius: 10px;
        font-weight: 600;
    }

    .form-control,
    .form-select,
    textarea {
        border-radius: 12px;
        border: 1px solid var(--border);
        padding: 0.75rem 0.9rem;
        box-shadow: none !important;
    }

    .form-control:focus,
    .form-select:focus,
    textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(109, 40, 217, 0.08) !important;
    }

    .alert-info {
        background-color: var(--primary-soft);
        border-color: var(--primary-border);
        color: var(--primary);
    }

    .badge-role {
        background-color: var(--primary-soft);
        color: var(--primary);
        border: 1px solid var(--primary-border);
        border-radius: 999px;
        padding: 0.45rem 0.8rem;
        font-weight: 600;
    }

    .shadow-soft {
        box-shadow: 0 8px 24px rgba(17, 24, 39, 0.04);
    }

    .table {
        border-color: var(--border);
    }

    .table thead th {
        background-color: #fafafa;
        color: var(--text);
        border-bottom: 1px solid var(--border);
        font-weight: 600;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .border-correct {
        border-left: 5px solid var(--success) !important;
    }

    .border-wrong {
        border-left: 5px solid var(--danger) !important;
    }

    .stat-card .stat-label {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-value {
        color: var(--text);
        font-size: 2rem;
        font-weight: 700;
    }

    .content-wrap {
        max-width: 1100px;
        margin: 0 auto;
    }

    .soft-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 24px rgba(17, 24, 39, 0.04);
    }
    .display-5 {
        letter-spacing: -0.02em;
    }

    .lead {
        font-size: 1.15rem;
        line-height: 1.7;
    }

    .btn-lg {
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
    }

    .content-wrap {
        max-width: 1360px;
        margin: 0 auto;
    }
</style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">English Grammar System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    @auth
                        
                        @if(Auth::user()->role === 'admin')
                            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active-nav' : '' }}" href="{{ route('admin.categories.index') }}">
                                Admin Categories
                            </a>

                            <a class="nav-link {{ request()->routeIs('admin.topics.*') ? 'active-nav' : '' }}" href="{{ route('admin.topics.index') }}">
                                Admin Topics
                            </a>

                            <a class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active-nav' : '' }}" href="{{ route('admin.questions.index') }}">
                                Admin Questions
                            </a>
                        @endif
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}" href="{{ route('dashboard') }}">
                            Dashboard
                        </a>

                        <a class="nav-link {{ request()->routeIs('grammar.check') ? 'active-nav' : '' }}" href="{{ route('grammar.check') }}">
                            Grammar Checker
                        </a>

                        <a class="nav-link {{ request()->routeIs('grammar.history') ? 'active-nav' : '' }}" href="{{ route('grammar.history') }}">
                            History
                        </a>

                        <a class="nav-link {{ request()->routeIs('quiz.categories', 'quiz.topics', 'quiz.start', 'quiz.submit') ? 'active-nav' : '' }}" href="{{ route('quiz.categories') }}">
                            Quiz
                        </a>

                        <a class="nav-link {{ request()->routeIs('quiz.history') ? 'active-nav' : '' }}" href="{{ route('quiz.history') }}">
                            Quiz History
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white text-decoration-none p-0">
                                Logout
                            </button>
                        </form>
                        {{-- <span class="navbar-text text-white bg-white bg-opacity-10 rounded-pill px-3 py-2 me-lg-2 mb-2 mb-lg-0">
                            Signed in as <strong>{{ Auth::user()->name }}</strong>
                        </span> --}}
                        <span class="badge-role ms-3">
                            {{ Auth::user()->name }}
                        </span>
                        {{-- <span class="badge bg-light text-dark me-3">
                            Signed in as {{ Auth::user()->name }} ({{ Auth::user()->email }})
                        </span>
                        <span class="badge bg-light text-dark me-3">
                            {{ Auth::user()->name }} | {{ Auth::user()->email }} | Role: {{ Auth::user()->role }}
                        </span> --}}
                    @else
                        <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                        <a class="nav-link text-white" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        @yield('content')
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script></body>
</html>
