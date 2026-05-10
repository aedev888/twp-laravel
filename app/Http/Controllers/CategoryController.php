<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function show(Request $request, Taxonomy $taxonomy): View
    {
        $query = Product::where('status', 'published')
            ->whereHas('taxonomies', function ($q) use ($taxonomy) {
                $q->where('taxonomies.id', $taxonomy->id);
            });

        // Filtering
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('price_type', $request->type);
        }

        // Sorting
        switch ($request->get('sort')) {
            case 'popular':
                $query->orderBy('downloads_count', 'desc');
                break;
            case 'rating':
                // Rating sorting is complex with AVG, for now by views as proxy or just latest
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->latest('published_at');
                break;
        }

        $products = $query->with(['media', 'taxonomies'])
            ->paginate(12);

        return view('categories.show', compact('taxonomy', 'products'));
    }
}
