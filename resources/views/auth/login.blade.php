<x-guest-layout>
    <div>
        <h2 class="auth-title">Welcome back</h2>
        <p class="auth-subtitle">Sign in to continue your grammar learning journey.</p>

        <x-auth-session-status class="mb-3 text-success" :status="session('status')" />

        @if ($errors->any())
            <div class="validation-errors">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                    id="email"
                    class="form-control"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email"
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    class="form-control"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label text-muted-custom" for="remember_me">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>

            <p class="mb-0 text-muted-custom">
                Don’t have an account?
                <a href="{{ route('register') }}" class="auth-link">Create one</a>
            </p>
        </form>
    </div>
</x-guest-layout>