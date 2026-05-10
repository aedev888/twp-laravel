<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('status', 'published')
            ->when($request->type, fn ($q) => $q->whereHas('taxonomies', fn ($q) => $q->where('slug', $request->type)))
            ->latest('published_at')
            ->paginate(12);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        if ($product->status !== 'published') {
            abort(404);
        }

        return new ProductResource($product);
    }
}
