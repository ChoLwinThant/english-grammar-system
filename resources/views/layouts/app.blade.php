<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'English Grammar System') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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

    .dashboard-shell {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .dashboard-hero {
        position: relative;
        overflow: hidden;
        padding: 2rem;
        border: 1px solid rgba(109, 40, 217, 0.12);
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(109, 40, 217, 0.16), transparent 34%),
            linear-gradient(135deg, #ffffff 0%, #f7f7ff 46%, #eef4ff 100%);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
    }

    .dashboard-hero::after {
        content: '';
        position: absolute;
        right: -60px;
        bottom: -80px;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.45);
        filter: blur(4px);
    }

    .dashboard-hero-content,
    .dashboard-hero-actions {
        position: relative;
        z-index: 1;
    }

    .dashboard-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.9rem;
        padding: 0.4rem 0.78rem;
        border: 1px solid rgba(109, 40, 217, 0.14);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.78);
        color: var(--primary);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .dashboard-hero-title {
        max-width: 14ch;
        margin: 0;
        font-size: clamp(2rem, 4vw, 3.35rem);
        line-height: 1.02;
        letter-spacing: -0.04em;
    }

    .dashboard-hero-copy {
        max-width: 52ch;
        margin: 1rem 0 0;
        color: #4b5563;
        font-size: 1.02rem;
        line-height: 1.8;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .dashboard-stat {
        padding: 1.25rem 1.35rem;
        border: 1px solid rgba(255, 255, 255, 0.45);
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.72);
        backdrop-filter: blur(10px);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
    }

    .dashboard-stat-label {
        margin: 0 0 0.4rem;
        color: var(--text-muted);
        font-size: 0.86rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .dashboard-stat-value {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.6rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
    }

    .dashboard-stat-note {
        margin: 0.55rem 0 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .dashboard-body {
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) minmax(300px, 0.95fr);
        gap: 1.5rem;
        align-items: start;
    }

    .dashboard-stack {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .dashboard-panel {
        padding: 1.6rem;
        border: 1px solid var(--border);
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #fcfcff 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .dashboard-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .dashboard-panel-title {
        margin: 0;
        font-size: 1.2rem;
    }

    .dashboard-panel-copy {
        margin: 0.4rem 0 0;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .dashboard-eyebrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.38rem 0.72rem;
        border-radius: 999px;
        background: #f3f0ff;
        color: var(--primary);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-highlight {
        display: grid;
        gap: 1rem;
    }

    .dashboard-highlight-block {
        padding: 1rem 1.05rem;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
    }

    .dashboard-highlight-label {
        margin: 0 0 0.45rem;
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .dashboard-highlight-text {
        margin: 0;
        line-height: 1.7;
        color: #1f2937;
    }

    .dashboard-meta {
        margin-top: 1rem;
        color: var(--text-muted);
        font-size: 0.92rem;
    }

    .dashboard-fab {
        position: fixed;
        right: 1.5rem;
        bottom: 1.5rem;
        z-index: 1080;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.85rem;
    }

    .dashboard-fab-menu {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.7rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px) scale(0.96);
        transform-origin: bottom right;
        transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
        pointer-events: none;
    }

    .dashboard-fab.is-open .dashboard-fab-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .dashboard-tool {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        min-width: min(320px, calc(100vw - 6rem));
        padding: 0.82rem 0.95rem;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.95);
        color: inherit;
        text-decoration: none;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.14);
        backdrop-filter: blur(16px);
        transition: transform 0.16s ease, border-color 0.16s ease, box-shadow 0.16s ease, background-color 0.16s ease;
    }

    .dashboard-tool:hover {
        transform: translateX(-4px);
        border-color: rgba(109, 40, 217, 0.24);
        background: linear-gradient(180deg, #ffffff 0%, #f5f3ff 100%);
        box-shadow: 0 18px 38px rgba(109, 40, 217, 0.12);
        color: inherit;
    }

    .dashboard-tool-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.6rem;
        height: 2.6rem;
        border-radius: 14px;
        background: #f5f3ff;
        color: var(--primary);
        flex: 0 0 auto;
        box-shadow: inset 0 0 0 1px rgba(109, 40, 217, 0.08);
    }

    .dashboard-tool-icon i {
        font-size: 1.05rem;
        line-height: 1;
    }

    .dashboard-tool-body {
        min-width: 0;
    }

    .dashboard-tool-title {
        margin: 0;
        font-size: 0.97rem;
        font-weight: 700;
    }

    .dashboard-tool-copy {
        margin: 0.2rem 0 0;
        color: var(--text-muted);
        font-size: 0.88rem;
        line-height: 1.45;
    }

    .dashboard-fab-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 22px 40px rgba(79, 70, 229, 0.32);
        cursor: pointer;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .dashboard-fab-toggle:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 26px 46px rgba(79, 70, 229, 0.38);
    }

    .dashboard-fab-toggle-text {
        display: inline-block;
        font-size: 1.4rem;
        line-height: 1;
        transition: transform 0.18s ease;
    }

    .dashboard-fab.is-open .dashboard-fab-toggle-text {
        transform: rotate(45deg);
    }

    .dashboard-fab-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1070;
        background: transparent;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.18s ease, visibility 0.18s ease;
    }

    .dashboard-fab-backdrop.is-visible {
        opacity: 1;
        visibility: visible;
    }

    .dashboard-empty {
        padding: 1.25rem;
        border: 1px dashed #d7deeb;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        color: var(--text-muted);
        line-height: 1.7;
    }

    @media (max-width: 991.98px) {
        .dashboard-stats,
        .dashboard-body {
            grid-template-columns: 1fr;
        }

        .dashboard-hero {
            padding: 1.5rem;
            border-radius: 22px;
        }

        .dashboard-panel {
            padding: 1.25rem;
            border-radius: 20px;
        }

        .dashboard-panel-header {
            flex-direction: column;
        }

        .dashboard-tool {
            width: 100%;
            min-width: min(280px, calc(100vw - 3rem));
        }

        .dashboard-fab {
            right: 1rem;
            bottom: 1rem;
        }

        .dashboard-fab-toggle {
            width: 3.6rem;
            height: 3.6rem;
        }
    }
</style>
</head>
<body class="bg-light">
    @php
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';
        $isAdminRoute = request()->routeIs('admin.*');
    @endphp

    <style>
        .admin-shell {
            width: 100%;
            padding: 0;
        }

        .admin-layout {
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            gap: 0;
            align-items: start;
            min-height: calc(100vh - 89px);
            transition: grid-template-columns 0.2s ease;
        }

        .admin-layout.sidebar-collapsed {
            grid-template-columns: 88px minmax(0, 1fr);
        }

        .admin-sidebar {
            position: sticky;
            top: 0;
            min-height: calc(100vh - 89px);
            padding: 2rem 1.4rem 1.4rem;
            border-right: 1px solid var(--border);
            background: linear-gradient(180deg, #ffffff 0%, #faf7ff 100%);
            box-shadow: 18px 0 40px rgba(109, 40, 217, 0.04);
            display: flex;
            flex-direction: column;
            transition: padding 0.2s ease, width 0.2s ease;
        }

        .admin-sidebar-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .admin-sidebar-title {
            margin: 0 0 0.4rem;
            font-size: 1.2rem;
        }

        .admin-sidebar-copy {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.94rem;
            line-height: 1.6;
        }

        .admin-sidebar-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border: 1px solid var(--primary-border);
            border-radius: 12px;
            background: #fff;
            color: var(--primary);
            cursor: pointer;
            flex: 0 0 auto;
            transition: background-color 0.16s ease, border-color 0.16s ease, transform 0.16s ease;
        }

        .admin-sidebar-toggle:hover {
            background: var(--primary-soft);
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        .admin-sidebar-toggle svg {
            width: 1.1rem;
            height: 1.1rem;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .admin-nav-section {
            margin-bottom: 1.4rem;
        }

        .admin-nav-label {
            margin: 0 0 0.85rem;
            padding: 0 0.6rem;
            color: #94a3b8;
            font-size: 0.77rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.88rem 1rem;
            border: 1px solid transparent;
            border-radius: 16px;
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.16s ease, border-color 0.16s ease, color 0.16s ease,
                transform 0.16s ease, box-shadow 0.16s ease;
        }

        .admin-nav-link:hover {
            background: var(--primary-soft);
            border-color: var(--primary-border);
            color: var(--primary);
            transform: translateX(2px);
        }

        .admin-nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, #60517a 100%);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 14px 28px rgba(109, 40, 217, 0.24);
        }

        .admin-nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.25rem;
            height: 1.25rem;
            flex: 0 0 auto;
        }

        .admin-nav-icon svg {
            width: 100%;
            height: 100%;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .admin-sidebar-footer {
            margin-top: auto;
            padding-top: 1.2rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .admin-user-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
        }

        .admin-user-name {
            margin: 0;
            font-weight: 700;
        }

        .admin-user-role {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .admin-content {
            min-width: 0;
            padding: 2rem;
            transition: padding 0.2s ease;
        }

        .admin-mobile-bar {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 0.9rem 1rem;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(17, 24, 39, 0.04);
        }

        .admin-mobile-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
        }

        .admin-mobile-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            padding: 0.55rem 0.85rem;
            border: 1px solid var(--primary-border);
            border-radius: 12px;
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 600;
            line-height: 1;
        }

        .admin-mobile-toggle svg {
            width: 1rem;
            height: 1rem;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .admin-sidebar-overlay {
            display: none;
        }

        .admin-page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .admin-page-head p {
            margin: 0.3rem 0 0;
            color: var(--text-muted);
        }

        .admin-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .admin-toolbar .form-control,
        .admin-toolbar .form-select {
            width: auto;
            min-width: 420px;
        }

        .admin-toolbar .form-control {
            flex: 0 1 420px;
            max-width: 420px;
        }

        .admin-toolbar .form-select {
            flex: 0 1 280px;
            max-width: 280px;
        }

        .admin-toolbar-spacer {
            flex: 1 1 auto;
            min-width: 0;
        }

        .admin-toolbar-search {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            flex: 0 1 auto;
            margin-left: auto;
        }

        .admin-toolbar-search .form-control {
            margin-left: 0;
        }

        .admin-toolbar-actions {
            display: flex;
            flex-wrap: nowrap;
            gap: 0.6rem;
            align-items: center;
            flex: 0 0 auto;
        }

        .btn-admin {
            padding: 0.45rem 0.82rem;
            border-radius: 10px;
            font-size: 0.9rem;
            line-height: 1.2;
            min-height: 40px;
        }

        .btn-admin.btn-link {
            text-decoration: none;
        }

        .btn-admin-danger {
            color: #b91c1c;
            border-color: #fecaca;
            background: #fff5f5;
        }

        .btn-admin-danger:hover {
            color: #991b1b;
            border-color: #fca5a5;
            background: #fee2e2;
        }

        .sidebar-collapsed .admin-sidebar {
            padding-left: 0.9rem;
            padding-right: 0.9rem;
        }

        .sidebar-collapsed .admin-sidebar-head {
            justify-content: center;
        }

        .sidebar-collapsed .admin-sidebar-brand,
        .sidebar-collapsed .admin-nav-label,
        .sidebar-collapsed .admin-nav-link-text,
        .sidebar-collapsed .admin-user-info {
            display: none;
        }

        .sidebar-collapsed .admin-nav-link {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .sidebar-collapsed .admin-sidebar-footer {
            justify-content: center;
        }

        @media (max-width: 991.98px) {
            .admin-layout {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1045;
                width: min(290px, 86vw);
                min-height: 100vh;
                border-right: 1px solid var(--border);
                border-bottom: 0;
                padding: 1.5rem 1rem;
                transform: translateX(-100%);
                transition: transform 0.22s ease;
                overflow-y: auto;
            }

            .sidebar-mobile-open .admin-sidebar {
                transform: translateX(0);
            }

            .admin-sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1040;
                background: rgba(15, 23, 42, 0.35);
            }

            .sidebar-mobile-open .admin-sidebar-overlay {
                display: block;
            }

            .admin-page-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-content {
                padding: 1.25rem 1rem;
            }

            .admin-mobile-bar {
                display: flex;
            }

            .admin-toolbar .form-control,
            .admin-toolbar .form-select,
            .admin-toolbar-search,
            .admin-toolbar-actions {
                width: 100%;
                flex: 1 1 100%;
                max-width: none;
            }

            .admin-toolbar-spacer {
                display: none;
            }

            .admin-toolbar-actions {
                flex-wrap: wrap;
            }

            .admin-layout.sidebar-collapsed {
                grid-template-columns: 1fr;
            }
        }
    </style>

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
                        @if(! $isAdminRoute)
                            @if($isAdmin)
                                <a class="nav-link {{ request()->routeIs('admin.categories.*', 'admin.topics.*', 'admin.questions.*') ? 'active-nav' : '' }}" href="{{ route('admin.categories.index') }}">
                                    Admin Panel
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
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-decoration-none p-0">
                                Logout
                            </button>
                        </form>
                        <span class="badge-role ms-3">
                            {{ Auth::user()->name }}
                        </span>
                    @else
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @php
        $userInitials = Auth::check()
            ? collect(explode(' ', trim(Auth::user()->name)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('')
            : '';
    @endphp

    <main class="{{ $isAdmin && $isAdminRoute ? 'admin-shell' : 'container py-5' }}">
        @if($isAdmin && $isAdminRoute)
            <div class="admin-layout" id="adminLayout">
                <button type="button" class="admin-sidebar-overlay" id="adminSidebarOverlay" aria-label="Close sidebar"></button>
                <aside class="admin-sidebar">
                    <div class="admin-sidebar-head">
                        <div class="admin-sidebar-brand">
                            <h2 class="admin-sidebar-title">Admin Panel</h2>
                        </div>
                        <button
                            type="button"
                            class="admin-sidebar-toggle"
                            id="adminSidebarToggle"
                            aria-label="Collapse sidebar"
                            aria-expanded="true"
                        >
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M15 18l-6-6 6-6"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="admin-nav-section">
                        <p class="admin-nav-label">Menu</p>
                        <nav class="admin-nav">
                            <a class="admin-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <span class="admin-nav-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"></path><path d="M5 9.5V21h14V9.5"></path><path d="M10 21v-6h4v6"></path></svg>
                                </span>
                                <span class="admin-nav-link-text">Dashboard</span>
                            </a>
                            <a class="admin-nav-link {{ request()->routeIs('quiz.categories', 'quiz.topics', 'quiz.start', 'quiz.submit', 'quiz.history') ? 'active' : '' }}" href="{{ route('quiz.categories') }}">
                                <span class="admin-nav-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 3h6"></path><path d="M10 8h4"></path><path d="M5 5h14l-1 13a3 3 0 0 1-3 3H9a3 3 0 0 1-3-3L5 5Z"></path></svg>
                                </span>
                                <span class="admin-nav-link-text">Practice Area</span>
                            </a>
                        </nav>
                    </div>

                    <div class="admin-nav-section">
                        <p class="admin-nav-label">Content</p>
                        <nav class="admin-nav">
                            <a class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                <span class="admin-nav-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h7v6H4z"></path><path d="M13 5h7v6h-7z"></path><path d="M4 13h7v6H4z"></path><path d="M13 13h7v6h-7z"></path></svg>
                                </span>
                                <span class="admin-nav-link-text">Categories</span>
                            </a>
                            <a class="admin-nav-link {{ request()->routeIs('admin.topics.*') ? 'active' : '' }}" href="{{ route('admin.topics.index') }}">
                                <span class="admin-nav-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16"></path><path d="M4 12h10"></path><path d="M4 18h16"></path></svg>
                                </span>
                                <span class="admin-nav-link-text">Topics</span>
                            </a>
                            <a class="admin-nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}" href="{{ route('admin.questions.index') }}">
                                <span class="admin-nav-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.09 9a3 3 0 1 1 5.82 1c0 2-3 3-3 3"></path><path d="M12 17h.01"></path><path d="M5 4h14v16H5z"></path></svg>
                                </span>
                                <span class="admin-nav-link-text">Questions</span>
                            </a>
                        </nav>
                    </div>

                    <div class="admin-sidebar-footer">
                        <span class="admin-user-mark">{{ $userInitials }}</span>
                        <div class="admin-user-info">
                            <p class="admin-user-name">{{ Auth::user()->name }}</p>
                            <p class="admin-user-role">Admin</p>
                        </div>
                    </div>
                </aside>

                <section class="admin-content">
                    <div class="admin-mobile-bar">
                        <p class="admin-mobile-title">Admin Menu</p>
                        <button type="button" class="admin-mobile-toggle" id="adminMobileSidebarToggle">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 7h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 17h16"></path>
                            </svg>
                            Menu
                        </button>
                    </div>

                    @yield('content')
                </section>
            </div>
        @else
            @yield('content')
        @endif
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const fab = document.getElementById('dashboardFab');
            const toggle = document.getElementById('dashboardFabToggle');
            const menu = document.getElementById('dashboardFabMenu');
            const backdrop = document.getElementById('dashboardFabBackdrop');

            if (!fab || !toggle || !menu || !backdrop) {
                return;
            }

            const setOpenState = (open) => {
                fab.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Close quick actions' : 'Open quick actions');
                backdrop.hidden = !open;
                backdrop.classList.toggle('is-visible', open);
            };

            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                setOpenState(!fab.classList.contains('is-open'));
            });

            backdrop.addEventListener('click', () => {
                setOpenState(false);
            });

            menu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    setOpenState(false);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    setOpenState(false);
                }
            });
        })();

        (() => {
            const layout = document.getElementById('adminLayout');
            const toggle = document.getElementById('adminSidebarToggle');
            const mobileToggle = document.getElementById('adminMobileSidebarToggle');
            const overlay = document.getElementById('adminSidebarOverlay');

            if (!layout || !toggle) {
                return;
            }

            const storageKey = 'admin-sidebar-collapsed';
            const mobileBreakpoint = 991;

            const isMobile = () => window.innerWidth <= mobileBreakpoint;

            const setMobileOpenState = (open) => {
                layout.classList.toggle('sidebar-mobile-open', open);
                document.body.style.overflow = open && isMobile() ? 'hidden' : '';
            };

            const setCollapsedState = (collapsed) => {
                if (isMobile()) {
                    return;
                }

                layout.classList.toggle('sidebar-collapsed', collapsed);
                toggle.setAttribute('aria-expanded', String(!collapsed));
                toggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
                toggle.innerHTML = collapsed
                    ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"></path></svg>'
                    : '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"></path></svg>';
            };

            const savedState = window.localStorage.getItem(storageKey) === 'true';
            if (!isMobile()) {
                setCollapsedState(savedState);
            }

            toggle.addEventListener('click', () => {
                if (isMobile()) {
                    setMobileOpenState(false);
                    return;
                }

                const nextState = !layout.classList.contains('sidebar-collapsed');
                setCollapsedState(nextState);
                window.localStorage.setItem(storageKey, String(nextState));
            });

            mobileToggle?.addEventListener('click', () => {
                setMobileOpenState(!layout.classList.contains('sidebar-mobile-open'));
            });

            overlay?.addEventListener('click', () => {
                setMobileOpenState(false);
            });

            window.addEventListener('resize', () => {
                if (isMobile()) {
                    layout.classList.remove('sidebar-collapsed');
                } else {
                    setMobileOpenState(false);
                    setCollapsedState(window.localStorage.getItem(storageKey) === 'true');
                }
            });
        })();
    </script>
</body>
</html>
