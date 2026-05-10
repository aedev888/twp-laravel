<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Checkout - Lumina Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <style>
        body {
            background: #0a0a0c;
            background-image: 
                radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 0% 100%, rgba(168, 85, 247, 0.08) 0%, transparent 40%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .checkout-wrapper {
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        @media (max-width: 900px) {
            .checkout-wrapper { grid-template-columns: 1fr; gap: 2rem; }
            .order-summary-side { order: -1; }
        }

        .payment-side {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            padding: 3.5rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.5);
        }

        /* Card Mockup */
        .card-mockup {
            width: 100%;
            aspect-ratio: 1.58 / 1;
            background: linear-gradient(135deg, #1e1e2d, #0f172a);
            border-radius: 1.25rem;
            padding: 2rem;
            position: relative;
            margin-bottom: 3rem;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5);
        }

        .card-mockup::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .card-chip {
            width: 50px;
            height: 40px;
            background: linear-gradient(135deg, #fbbf24, #d97706);
            border-radius: 0.5rem;
            margin-bottom: 3rem;
            position: relative;
        }

        .card-number {
            font-size: 1.5rem;
            letter-spacing: 0.2em;
            color: white;
            font-family: monospace;
            margin-bottom: 2rem;
            position: relative;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            position: relative;
        }

        .card-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 0.25rem;
        }

        .card-holder {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .order-summary-side {
            padding: 1rem;
        }

        .summary-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 2.5rem;
        }

        .product-item {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            margin-bottom: 2rem;
        }

        .product-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-gradient);
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }

        .summary-divider {
            height: 1px;
            background: var(--glass-border);
            margin: 2rem 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .summary-row.total {
            color: white;
            font-size: 1.5rem;
            font-weight: 900;
            margin-top: 1rem;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 1.25rem;
            color: white;
            font-family: inherit;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s;
        }

        .form-input:focus {
            border-color: var(--primary);
            background: rgba(255,255,255,0.06);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .payment-badges {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
            opacity: 0.5;
        }

        .payment-badges i { font-size: 1.5rem; }
    </style>
</head>
<body>
    <div class="checkout-wrapper">
        <!-- Payment Section -->
        <div class="payment-side">
            <h2 style="font-size: 2rem; font-weight: 900; margin-bottom: 2.5rem; letter-spacing: -0.02em;">Payment <span>Information</span></h2>
            
            <div class="card-mockup">
                <div class="card-chip"></div>
                <div class="card-number">**** **** **** 4242</div>
                <div class="card-bottom">
                    <div>
                        <div class="card-label">Card Holder</div>
                        <div class="card-holder">{{ $user->name }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div class="card-label">Expires</div>
                        <div style="font-weight: 600;">12/26</div>
                    </div>
                </div>
                <i class="fa-brands fa-cc-visa" style="position: absolute; top: 2rem; right: 2rem; font-size: 2.5rem; color: rgba(255,255,255,0.2);"></i>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.75rem; color: var(--text-muted); font-size: 0.875rem; font-weight: 600;">Full Name on Card</label>
                    <input type="text" class="form-input" value="{{ $user->name }}" placeholder="John Doe" required>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 3rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; color: var(--text-muted); font-size: 0.875rem; font-weight: 600;">Card Number</label>
                        <input type="text" class="form-input" placeholder="4242 4242 4242 4242" value="4242 4242 4242 4242" readonly>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; color: var(--text-muted); font-size: 0.875rem; font-weight: 600;">CVC</label>
                        <input type="text" class="form-input" placeholder="123" value="123" readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.25rem; font-size: 1.125rem; font-weight: 800; border-radius: 1.25rem; box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.5);">
                    <i class="fa-solid fa-lock" style="margin-right: 0.75rem;"></i> Complete Purchase
                </button>
            </form>

            <div class="payment-badges">
                <i class="fa-brands fa-cc-visa"></i>
                <i class="fa-brands fa-cc-mastercard"></i>
                <i class="fa-brands fa-cc-stripe"></i>
                <i class="fa-brands fa-cc-apple-pay"></i>
            </div>
        </div>

        <!-- Order Summary Section -->
        <div class="order-summary-side">
            <div style="margin-bottom: 3rem;">
                <a href="{{ url('/') }}" style="text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Marketplace
                </a>
            </div>

            <div class="summary-card">
                <h3 style="margin-bottom: 2rem;">Order <span>Summary</span></h3>
                
                <div class="product-item">
                    <div class="product-icon">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 1.125rem; margin-bottom: 0.25rem;">Lumina Premium</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Unlimited Access Plan</div>
                    </div>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>$19.00</span>
                </div>
                <div class="summary-row">
                    <span>Tax (0%)</span>
                    <span>$0.00</span>
                </div>
                
                <div class="summary-divider"></div>

                <div class="summary-row total">
                    <span>Total</span>
                    <span style="color: var(--primary);">$19.00</span>
                </div>

                <div style="margin-top: 3rem; background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 1rem; padding: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                    <i class="fa-solid fa-shield-check" style="color: #10b981; font-size: 1.5rem;"></i>
                    <div style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4;">
                        Your payment is processed securely. We don't store your full credit card details.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
