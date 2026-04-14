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

            --bg: #f8fafc;
            --surface: #ffffff;
            --text: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #faf8ff 0%, #f8fafc 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-wrap {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1fr 420px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(17, 24, 39, 0.08);
        }

        .auth-brand {
            background: linear-gradient(135deg, #faf5ff 0%, #f5f3ff 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid var(--border);
        }

        .brand-badge {
            display: inline-block;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--primary-border);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            width: fit-content;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .brand-title span {
            color: var(--primary);
        }

        .brand-text {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 420px;
        }

        .auth-panel {
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        .auth-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .auth-subtitle {
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.45rem;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 0.8rem 0.95rem;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(109, 40, 217, 0.08) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.1rem;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .text-muted-custom {
            color: var(--text-muted);
        }

        .validation-errors {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 991px) {
            .auth-wrap {
                grid-template-columns: 1fr;
                max-width: 560px;
            }

            .auth-brand {
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding: 2rem;
            }

            .auth-panel {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="auth-wrap">
            <div class="auth-brand">
                <div class="brand-badge">English Learning Platform</div>
                <h1 class="brand-title">
                    Improve your <span>English grammar</span> with confidence.
                </h1>
                <p class="brand-text">
                    Practice grammar, check sentences with AI, and track your learning progress in one simple system.
                </p>
            </div>

            <div class="auth-panel">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>