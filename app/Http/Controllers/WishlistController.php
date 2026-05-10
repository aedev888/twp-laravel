<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Product $product): RedirectResponse
    {
        $wishlistEntry = WishlistItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlistEntry) {
            $wishlistEntry->delete();
            $message = 'Removed from your wishlist.';
        } else {
            WishlistItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $message = 'Added to your wishlist.';
        }

        return back()->with('success', $message);
    }
}
