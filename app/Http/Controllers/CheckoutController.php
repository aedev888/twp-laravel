<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SubscriptionType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page.
     */
    public function checkout(Request $request): View
    {
        return view('checkout', [
            'user' => $request->user()
        ]);
    }

    /**
     * Process the mock payment.
     */
    public function process(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // In a real app, you would integrate Stripe here
        // For this demo, we simulate a successful payment
        
        $user->update([
            'subscription_type' => SubscriptionType::Premium,
            'subscription_ends_at' => now()->addMonth(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Payment successful! Welcome to Lumina Premium.');
    }
}
