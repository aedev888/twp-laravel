<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Product;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ─── Rate Limiting ──────────────────────────────────────────────────
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // ─── Morph Map ──────────────────────────────────────────────────────
        Relation::morphMap([
            'product' => \App\Models\Product::class,
            'page'    => \App\Models\Page::class,
        ]);

        // ─── Observers ──────────────────────────────────────────────────────
        Product::observe(ProductObserver::class);

        // ─── Global View Data ───────────────────────────────────────────────
        \Illuminate\Support\Facades\View::composer(['layouts.sidebar', 'layouts.right-sidebar', 'welcome', 'categories.show', 'products.show', 'reviews.index', 'blog.index', 'blog.show'], function ($view) {
            $view->with('categories', \App\Models\Taxonomy::where('type', 'category')->get());
            $view->with('latestReviews', \App\Models\Review::where('is_published', true)->with(['user', 'product'])->latest()->limit(3)->get());
            $view->with('popularAssets', \App\Models\Product::where('status', 'published')->orderBy('views_count', 'desc')->limit(8)->get());
            
            // Only fetch stats if we are on welcome page or similar
            if ($view->getName() === 'welcome' || $view->getName() === 'reviews.index') {
                $view->with('stats', [
                    'products'  => \App\Models\Product::count(),
                    'downloads' => \App\Models\DownloadLog::count(),
                    'premium'   => \App\Models\Product::where('price_type', 'premium')->count(),
                ]);
            }
        });
    }
}
