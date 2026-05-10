<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DownloadLog;
use App\Models\Review;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $tab = $request->query('tab', 'overview');
        
        $data = [
            'user' => $user,
            'tab' => $tab,
        ];

        switch ($tab) {
            case 'downloads':
                $data['downloads'] = DownloadLog::where('user_id', $user->id)
                    ->with('product')
                    ->orderBy('downloaded_at', 'desc')
                    ->paginate(15);
                break;
            
            case 'wishlist':
                $data['wishlistItems'] = $user->wishlist()
                    ->with('product')
                    ->latest()
                    ->get();
                break;

            case 'collections':
                $data['collections'] = Collection::where('user_id', $user->id)
                    ->with('products')
                    ->latest()
                    ->get();
                break;

            case 'reviews':
                $data['reviews'] = Review::where('user_id', $user->id)
                    ->with('product')
                    ->latest()
                    ->get();
                break;

            default: // Overview
                $data['recentDownloads'] = DownloadLog::where('user_id', $user->id)
                    ->with('product')
                    ->orderBy('downloaded_at', 'desc')
                    ->limit(5)
                    ->get();
                $data['wishlistItems'] = $user->wishlist()
                    ->with('product')
                    ->latest()
                    ->limit(4)
                    ->get();
                break;
        }

        return view('dashboard', $data);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function storeCollection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        Auth::user()->collections()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'is_public' => $request->has('is_public'),
        ]);

        return back()->with('success', 'Collection created successfully!');
    }

    public function toggleCollectionProduct(Collection $collection, Product $product): RedirectResponse
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $collection->products()->toggle($product->id);

        return back()->with('success', 'Collection updated!');
    }
}
