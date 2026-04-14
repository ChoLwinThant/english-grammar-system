<x-guest-layout>
    <div>
        <h2 class="auth-title">Create your account</h2>
        <p class="auth-subtitle">Start practicing grammar, taking quizzes, and tracking your progress.</p>

        @if ($errors->any())
            <div class="validation-errors">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Full name</label>
                <input
                    id="name"
                    class="form-control"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your name"
                >
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                    id="email"
                    class="form-control"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
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
                    autocomplete="new-password"
                    placeholder="Create a password"
                >
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm password</label>
                <input
                    id="password_confirmation"
                    class="form-control"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                >
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>

            <p class="mb-0 text-muted-custom">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-link">Sign in</a>
            </p>
        </form>
    </div>
</x-guest-layout>