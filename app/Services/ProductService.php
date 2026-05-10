<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DownloadLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class ProductService
{
    public function canDownload(?User $user, Product $product): bool
    {
        // Must be logged in to download anything
        if (!$user) {
            return false;
        }

        // Admins can download everything
        if ($user->role === \App\Enums\UserRole::Admin) {
            return true;
        }

        // Free products can be downloaded by any registered user
        if ($product->price_type === \App\Enums\ProductPriceType::Free) {
            return true;
        }

        // Premium products require active subscription
        return $this->isSubscriber($user);
    }

    public function isSubscriber(User $user): bool
    {
        // Check if user has an active premium subscription
        return $user->subscription_type === \App\Enums\SubscriptionType::Premium;
    }

    public function recordDownload(User $user, Product $product): void
    {
        DownloadLog::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'downloaded_at' => now(),
        ]);

        $product->increment('downloads_count');
    }
}
