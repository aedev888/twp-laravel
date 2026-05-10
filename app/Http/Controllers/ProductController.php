<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::where('status', 'published');

        if ($request->filled('category')) {
            $query->whereHas('taxonomies', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->with(['taxonomies', 'media'])
            ->latest('published_at')
            ->paginate(12);

        return view('welcome', [
            'featuredProducts' => $products,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title->en', 'like', "%{$query}%")
                  ->orWhere('title->ru', 'like', "%{$query}%");
            })
            ->with('media')
            ->limit(5)
            ->get();

        return response()->json($products->map(fn($p) => [
            'title' => $p->title,
            'slug' => $p->slug,
            'preview' => $p->getPreviewUrl(),
            'price_type' => $p->price_type?->label() ?? 'Free',
        ]));
    }

    public function show(Product $product): View
    {
        $product->load(['taxonomies', 'media']);
        $product->increment('views_count');

        // Load related products from the same category
        $relatedProducts = Product::where('status', 'published')
            ->where('id', '!=', $product->id)
            ->whereHas('taxonomies', function ($query) use ($product) {
                $query->whereIn('taxonomies.id', $product->taxonomies->pluck('id'));
            })
            ->with(['media', 'taxonomies'])
            ->limit(3)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
