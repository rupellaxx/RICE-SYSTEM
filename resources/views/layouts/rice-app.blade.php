<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'Rice System') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #faf8f4;
            --surface:   #ffffff;
            --surface-2: #f3f0ea;
            --border:    #e0dbd1;
            --text:      #1c1a16;
            --muted:     #8c8578;
            --accent:    #d4410e;
            --accent-hov:#b33309;
            --accent-soft:#fef3ee;
            --green:     #2d7a4f;
            --green-soft:#edf7f2;
            --ff-mono:   'DM Mono', monospace;
            --ff-serif:  'Cormorant Garamond', serif;
        }

        html, body {
            min-height: 100%;
            background: var(--bg);
            color: var(--text);
            font-family: var(--ff-mono);
            font-size: 13px;
            font-weight: 300;
            line-height: 1.6;
        }

        /* ── Nav ── */
        .nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            font-family: var(--ff-serif);
            font-size: 1.25rem;
            font-weight: 300;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -.01em;
        }

        .nav-brand em { font-style: italic; color: var(--accent); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .nav-link {
            padding: 0 16px;
            height: 52px;
            display: flex;
            align-items: center;
            font-size: 10px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--muted);
            text-decoration: none;
            border-left: 1px solid var(--border);
            transition: color .15s, background .15s;
        }

        .nav-link:hover, .nav-link.active { color: var(--text); background: var(--surface-2); }

        .nav-logout {
            background: none;
            border: none;
            border-left: 1px solid var(--border);
            padding: 0 16px;
            height: 52px;
            font-family: var(--ff-mono);
            font-size: 10px;
            font-weight: 300;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--muted);
            cursor: pointer;
            transition: color .15s, background .15s;
        }

        .nav-logout:hover { color: var(--accent); background: var(--accent-soft); }

        .nav-user {
            padding: 0 16px;
            height: 52px;
            display: flex;
            align-items: center;
            font-size: 10px;
            letter-spacing: .1em;
            color: var(--muted);
            border-left: 1px solid var(--border);
        }

        /* ── Page ── */
        .page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 36px 32px 64px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-eyebrow {
            font-size: 9px;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-eyebrow::before {
            content: '';
            width: 16px;
            height: 1px;
            background: var(--accent);
        }

        .page-title {
            font-family: var(--ff-serif);
            font-size: 2rem;
            font-weight: 300;
            letter-spacing: -.01em;
            line-height: 1;
            color: var(--text);
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 24px;
        }

        .card-title {
            font-size: 9px;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ── Tables ── */
        .tbl { width: 100%; border-collapse: collapse; }
        .tbl th {
            text-align: left;
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 8px 12px;
            border-bottom: 1px solid var(--border);
            font-weight: 400;
        }
        .tbl td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }
        .tbl tr:last-child td { border-bottom: none; }
        .tbl tr:hover td { background: var(--surface-2); }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 9px;
            letter-spacing: .15em;
            text-transform: uppercase;
            border: 1px solid currentColor;
        }
        .badge-green { color: var(--green); background: var(--green-soft); }
        .badge-orange { color: var(--accent); background: var(--accent-soft); }
        .badge-gray { color: var(--muted); background: var(--surface-2); }

        /* ── Forms ── */
        .field { margin-bottom: 16px; }

        .field label {
            display: block;
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }

        input[type="text"], input[type="number"], input[type="email"],
        input[type="password"], select, textarea {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--ff-mono);
            font-size: 13px;
            font-weight: 300;
            padding: 10px 12px;
            outline: none;
            border-radius: 0;
            -webkit-appearance: none;
            transition: border-color .15s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            background: var(--surface);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            font-family: var(--ff-mono);
            font-size: 10px;
            font-weight: 400;
            letter-spacing: .2em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-hov); }

        .btn-ghost {
            background: none;
            color: var(--muted);
            border: 1px solid var(--border);
            padding: 8px 14px;
        }
        .btn-ghost:hover { color: var(--text); border-color: var(--text); }

        .btn-danger {
            background: none;
            color: #c0392b;
            border: 1px solid #f0c0bb;
            padding: 8px 14px;
        }
        .btn-danger:hover { background: #fdf3f2; }

        .btn-sm { padding: 6px 12px; font-size: 9px; }

        /* ── Alert ── */
        .alert {
            padding: 12px 16px;
            font-size: 11px;
            margin-bottom: 20px;
            border-left: 3px solid;
        }
        .alert-success { background: var(--green-soft); border-color: var(--green); color: var(--green); }
        .alert-error { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }

        /* ── Utility ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mt-6 { margin-top: 24px; }
        .text-muted { color: var(--muted); }
        .text-accent { color: var(--accent); }
        .text-sm { font-size: 11px; }
        .text-right { text-align: right; }
        .w-full { width: 100%; }

        @media (max-width: 768px) {
            .nav { padding: 0 16px; }
            .page { padding: 24px 16px 48px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .nav-user { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="nav">
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <em>Rice</em> System
    </a>
    <div class="nav-links">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Orders</a>
        <a href="{{ route('rice.index') }}" class="nav-link {{ request()->routeIs('rice.*') ? 'active' : '' }}">Rice Menu</a>
        @auth
            <span class="nav-user">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:contents;">
                @csrf
                <button type="submit" class="nav-logout">Logout</button>
            </form>
        @endauth
    </div>
</nav>

<div class="page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

@stack('scripts')
</body>
</html>