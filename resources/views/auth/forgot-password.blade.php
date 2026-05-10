<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Lumina Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ url('/') }}" class="logo" style="font-size: 2.5rem;">LUMINA<span>CMS</span></a>
                <h2 style="margin-top: 1.5rem; font-weight: 900; letter-spacing: -0.02em; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Recover Password</h2>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Enter your email to receive a reset link.</p>
            </div>

            @if (session('status'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                    @error('email') <span class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 2rem; font-size: 1.1rem;">
                    Send Reset Link
                </button>
            </form>

            <div class="auth-links">
                <p>
                    Remember your password? <a href="{{ route('login') }}">Back to Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
