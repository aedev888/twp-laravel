<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PageService
{
    public function getPaginatedPages(int $perPage = 15, ?string $taxonomySlug = null): LengthAwarePaginator
    {
        $query = Page::with(['taxonomies', 'media'])->where('status', 'published');
        
        if ($taxonomySlug) {
            $query->whereHas('taxonomies', function($q) use ($taxonomySlug) {
                $q->where('slug->en', $taxonomySlug) // fallback simple check
                  ->orWhere('slug->ru', $taxonomySlug);
            });
        }
        
        return $query->paginate($perPage);
    }
    
    public function getPageBySlug(string $slug): ?Page
    {
        return Page::with(['taxonomies', 'media'])
            ->where('status', 'published')
            ->where(function($query) use ($slug) {
                $query->where('slug->en', $slug)
                      ->orWhere('slug->ru', $slug);
            })->first();
    }
}
