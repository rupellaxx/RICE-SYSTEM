<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in — {{ config('app.name', 'Rice System') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #faf8f4;
            --surface:    #ffffff;
            --surface-2:  #f3f0ea;
            --border:     #e0dbd1;
            --text:       #1c1a16;
            --muted:      #8c8578;
            --accent:     #d4410e;
            --accent-hov: #b33309;
            --accent-soft:#fef3ee;
            --ff-mono:    'DM Mono', monospace;
            --ff-serif:   'Cormorant Garamond', serif;
        }

        html, body {
            height: 100%;
            background: var(--bg);
            color: var(--text);
            font-family: var(--ff-mono);
            font-size: 13px;
            font-weight: 300;
            line-height: 1.6;
        }

        /* ── Subtle grain texture ── */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='.025'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* ── Warm accent glow top-right ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 55% 45% at 100% 0%, rgba(212,65,14,.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 0% 100%, rgba(212,65,14,.04) 0%, transparent 55%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Layout: two columns ── */
        .shell {
            position: relative;
            z-index: 1;
            min-height: 100%;
            display: grid;
            grid-template-columns: 1fr 460px;
        }

        /* ── Left panel ── */
        .left {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            border-right: 1px solid var(--border);
            opacity: 0;
            animation: fadeLeft .8s .05s cubic-bezier(.22,1,.36,1) forwards;
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .brand-logo {
            font-family: var(--ff-serif);
            font-size: 1.4rem;
            font-weight: 300;
            color: var(--text);
            letter-spacing: -.01em;
        }

        .brand-logo em { font-style: italic; color: var(--accent); }

        .brand-tagline {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--muted);
        }

        /* ── Big serif headline ── */
        .left-hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 64px 0;
        }

        .left-eyebrow {
            font-size: 9px;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .left-eyebrow::before {
            content: '';
            width: 20px;
            height: 1px;
            background: var(--accent);
            display: block;
        }

        .left-headline {
            font-family: var(--ff-serif);
            font-size: clamp(3rem, 4.5vw, 5rem);
            font-weight: 300;
            line-height: .95;
            letter-spacing: -.02em;
            color: var(--text);
        }

        .left-headline em {
            font-style: italic;
            color: var(--accent);
        }

        .left-desc {
            margin-top: 24px;
            font-size: 11px;
            color: var(--muted);
            max-width: 340px;
            line-height: 1.8;
        }

        /* ── Feature list ── */
        .features {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding-top: 32px;
            border-top: 1px solid var(--border);
        }

        .feature {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .feature-num {
            font-size: 9px;
            letter-spacing: .15em;
            color: var(--accent);
            min-width: 20px;
            padding-top: 2px;
        }

        .feature-text {
            font-size: 11px;
            color: var(--muted);
            line-height: 1.5;
        }

        .feature-text strong {
            display: block;
            color: var(--text);
            font-weight: 400;
            font-size: 12px;
            margin-bottom: 2px;
        }

        /* ── Right panel: the form ── */
        .right {
            background: var(--surface);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 52px;
            min-height: 100vh;
            opacity: 0;
            animation: fadeUp .9s .15s cubic-bezier(.22,1,.36,1) forwards;
        }

        .form-eyebrow {
            font-size: 9px;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .form-title {
            font-family: var(--ff-serif);
            font-size: 2.2rem;
            font-weight: 300;
            letter-spacing: -.01em;
            line-height: 1.1;
            color: var(--text);
            margin-bottom: 40px;
        }

        /* ── Divider line ── */
        .form-divider {
            width: 40px;
            height: 1px;
            background: var(--accent);
            margin-bottom: 36px;
        }

        /* ── Fields ── */
        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-size: 9px;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 7px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--ff-mono);
            font-size: 13px;
            font-weight: 300;
            padding: 12px 14px;
            outline: none;
            border-radius: 0;
            -webkit-appearance: none;
            transition: border-color .15s, background .15s;
        }

        input::placeholder { color: #c8c3ba; }

        input:focus {
            border-color: var(--accent);
            background: var(--surface);
        }

        /* ── Error text ── */
        .field-error {
            margin-top: 5px;
            font-size: 10px;
            color: var(--accent);
            letter-spacing: .05em;
        }

        /* ── Remember / forgot row ── */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 22px 0 28px;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 10px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            user-select: none;
        }

        .check-label input[type="checkbox"] {
            width: 14px;
            height: 14px;
            appearance: none;
            border: 1px solid var(--border);
            background: var(--bg);
            cursor: pointer;
            position: relative;
            padding: 0;
            flex-shrink: 0;
            transition: border-color .15s, background .15s;
        }

        .check-label input[type="checkbox"]:checked {
            background: var(--accent);
            border-color: var(--accent);
        }

        .check-label input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 10 10'%3E%3Cpath d='M2 5l2.5 2.5L8 3' stroke='white' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") center/10px no-repeat;
        }

        .forgot {
            font-size: 10px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }

        .forgot:hover { color: var(--accent); }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: 14px 20px;
            background: var(--accent);
            color: #fff;
            border: none;
            font-family: var(--ff-mono);
            font-size: 10px;
            font-weight: 400;
            letter-spacing: .25em;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background .15s;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,.1);
            transform: translateX(-100%);
            transition: transform .3s cubic-bezier(.22,1,.36,1);
        }

        .btn-submit:hover { background: var(--accent-hov); }
        .btn-submit:hover::after { transform: translateX(0); }

        /* ── Register section ── */
        .register-section {
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .register-section p {
            font-size: 10px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .btn-register {
            display: inline-flex;
            align-items: center;
            padding: 9px 16px;
            border: 1px solid var(--border);
            background: none;
            color: var(--muted);
            font-family: var(--ff-mono);
            font-size: 10px;
            letter-spacing: .15em;
            text-transform: uppercase;
            text-decoration: none;
            white-space: nowrap;
            transition: color .15s, border-color .15s, background .15s;
        }

        .btn-register:hover {
            color: var(--text);
            border-color: var(--text);
            background: var(--surface-2);
        }

        /* ── Animations ── */
        @keyframes fadeLeft {
            to { opacity: 1; }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .shell {
                grid-template-columns: 1fr;
            }
            .left {
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding: 36px 32px;
                min-height: auto;
            }
            .left-hero { padding: 36px 0; }
            .left-headline { font-size: 2.8rem; }
            .features { display: none; }
            .right {
                min-height: auto;
                padding: 40px 32px 56px;
            }
        }
    </style>
</head>
<body>

<div class="shell">

    <!-- ── Left Panel ── -->
    <div class="left">

        <div class="brand">
            <div class="brand-logo"><em>Rice</em> System</div>
            <div class="brand-tagline">Order Management</div>
        </div>

        <div class="left-hero">
            <p class="left-eyebrow">Welcome</p>
            <h1 class="left-headline">
                Manage<br>
                <em>orders</em><br>
                with ease.
            </h1>
            <p class="left-desc">
                A streamlined system for tracking rice orders, managing your menu, and recording payments — all in one place.
            </p>
        </div>

        <div class="features">
            <div class="feature">
                <span class="feature-num">01</span>
                <div class="feature-text">
                    <strong>Place Orders</strong>
                    Select rice items and quantities in seconds.
                </div>
            </div>
            <div class="feature">
                <span class="feature-num">02</span>
                <div class="feature-text">
                    <strong>Track Payments</strong>
                    Mark orders as paid and keep records clean.
                </div>
            </div>
            <div class="feature">
                <span class="feature-num">03</span>
                <div class="feature-text">
                    <strong>Manage Menu</strong>
                    Add, edit, or remove rice items anytime.
                </div>
            </div>
        </div>

    </div>

    <!-- ── Right Panel: Login Form ── -->
    <div class="right">

        <p class="form-eyebrow">Account Access</p>
        <h2 class="form-title">Sign in to<br>your account</h2>
        <div class="form-divider"></div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autofocus
                    autocomplete="username"
                >
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••••••"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-row">
                <label class="check-label">
                    <input type="checkbox" name="remember" id="remember_me">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">Sign in →</button>
        </form>

        @if (Route::has('register'))
            <div class="register-section">
                <p>No account yet?</p>
                <a href="{{ route('register') }}" class="btn-register">Create one →</a>
            </div>
        @endif

    </div>

</div>

</body>
</html>