<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Lumina Marketplace</title>
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
                <p style="color: var(--text-muted); margin-top: 1rem; font-weight: 500;">Welcome back! Please login to your account.</p>
            </div>

            @if (session('status'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                    @error('email') <span class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <label style="margin-bottom: 0;">Password</label>
                        <a href="{{ route('password.request') }}" style="color: var(--primary); font-size: 0.8rem; text-decoration: none; font-weight: 600;">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password') <span class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color: var(--primary); width: 1.2rem; height: 1.2rem; cursor: pointer;">
                    <label for="remember" style="margin: 0; font-size: 0.875rem; color: var(--text-muted); cursor: pointer;">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 2.5rem; font-size: 1.1rem;">
                    Log In to Marketplace
                </button>
            </form>

            <div class="auth-links">
                <p>
                    New to Lumina? <a href="{{ route('register') }}">Create an account</a>
                </p>
            </div>
            
            <!-- Telegram Login Widget -->
            <div style="margin-top: 2rem; display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600;">Or Login With</span>
                <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="{{ config('services.telegram.bot_username', 'LuminaMarketBot') }}" data-size="large" data-radius="12" data-auth-url="{{ route('auth.telegram') }}" data-request-access="write"></script>
            </div>
        </div>
    </div>
</body>
</html>
