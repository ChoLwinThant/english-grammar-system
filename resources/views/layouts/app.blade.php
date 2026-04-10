<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'English Grammar System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        
                        <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="nav-link text-white" href="{{ route('grammar.check') }}">Grammar Checker</a>
                        <a class="nav-link text-white" href="{{ route('grammar.history') }}">History</a>

                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white text-decoration-none p-0">
                                Logout
                            </button>
                        </form>
                        <span class="navbar-text text-white bg-white bg-opacity-10 rounded-pill px-3 py-2 me-lg-2 mb-2 mb-lg-0">
                            Signed in as <strong>{{ Auth::user()->name }}</strong>
                        </span>
                    @else
                        <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                        <a class="nav-link text-white" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
