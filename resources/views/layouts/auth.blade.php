<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Lumina Marketplace</title>
    <meta name="description" content="@yield('description', 'Access thousands of premium WordPress themes, plugins and kits.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Ambient background blobs */
        .auth-page::before {
            content: '';
            position: fixed;
            top: -20%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.18) 0%, transparent 70%);
            pointer-events: none;
            animation: float 8s ease-in-out infinite alternate;
        }

        .auth-page::after {
            content: '';
            position: fixed;
            bottom: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.14) 0%, transparent 70%);
            pointer-events: none;
            animation: float 10s ease-in-out infinite alternate-reverse;
        }

        @keyframes float {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, -30px) scale(1.05); }
        }

        .auth-logo {
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .auth-logo a {
            font-size: 1.75rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-decoration: none;
            color: white;
        }

        .auth-logo a span {
            color: var(--primary);
        }

        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(20px);
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.4);
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-card h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0 0 0.375rem;
        }

        .auth-card h1 span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-card .subtitle {
            color: var(--text-muted);
            font-size: 0.925rem;
            margin: 0 0 2rem;
        }

        /* ─── Form elements ─── */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--glass-border);
            border-radius: 0.75rem;
            padding: 0.8rem 1rem;
            color: white;
            font-family: inherit;
            font-size: 0.925rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        .form-input::placeholder { color: rgba(255, 255, 255, 0.2); }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            background: rgba(255, 255, 255, 0.06);
        }

        .form-input.is-invalid {
            border-color: #f87171;
        }

        .field-error {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 0.375rem;
        }

        /* ─── Buttons ─── */
        .btn-auth {
            width: 100%;
            padding: 0.875rem;
            border: none;
            border-radius: 0.75rem;
            background: var(--primary-gradient);
            color: white;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            margin-top: 0.5rem;
        }

        .btn-auth:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        /* ─── Divider ─── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.75rem 0;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--glass-border);
        }

        /* ─── Telegram button area ─── */
        .telegram-wrapper {
            display: flex;
            justify-content: center;
            min-height: 44px;
            align-items: center;
        }

        /* ─── Remember me ─── */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .remember-row label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--text-muted);
        }

        .remember-row input[type="checkbox"] {
            accent-color: var(--primary);
        }

        /* ─── Bottom link ─── */
        .auth-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        /* ─── Alert box ─── */
        .alert-error {
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.3);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            color: #fca5a5;
            font-size: 0.875rem;
        }

        .alert-success {
            background: rgba(52, 211, 153, 0.1);
            border: 1px solid rgba(52, 211, 153, 0.3);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            color: #6ee7b7;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-logo">
            <a href="{{ url('/') }}">LUMINA<span>CMS</span></a>
        </div>

        <div class="auth-card">
            {{-- Global alerts --}}
            @if(session('success'))
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->has('telegram'))
                <div class="alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ $errors->first('telegram') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
