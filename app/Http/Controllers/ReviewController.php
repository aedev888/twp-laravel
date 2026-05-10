<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::where('is_published', true)
            ->with(['user', 'product'])
            ->latest()
            ->paginate(20);

        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'content' => 'required|string|min:5',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'content' => $request->content,
            'rating' => $request->rating,
            'is_published' => true, // Auto-publish for now or use moderation
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
