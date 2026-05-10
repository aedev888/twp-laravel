<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\ProductStatus;
use App\Mail\NewProductAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Only send alert if status changed from something else to 'published'
        if ($product->isDirty('status') && $product->status === ProductStatus::published) {
            $this->sendNewProductAlert($product);
        }
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        if ($product->status === ProductStatus::published) {
            $this->sendNewProductAlert($product);
        }
    }

    protected function sendNewProductAlert(Product $product): void
    {
        // For production, we would use a queued job and chunk users
        // For now, we'll send to all users who have notifications enabled (if we had that field)
        $users = User::all();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NewProductAlert($product));
        }
    }
}
