<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $taxonomy->name }} - Lumina Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles
</head>
<body>
    @include('layouts.navbar')

    <header class="category-hero">
        <div style="max-width: 800px; margin: 0 auto;">
            <span style="color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; font-size: 0.8rem;">Browse Category</span>
            <h1 style="font-size: 4rem; font-weight: 950; margin: 1rem 0; letter-spacing: -0.04em;">{{ $taxonomy->name }}</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">
                Explore our curated collection of high-quality {{ strtolower($taxonomy->name) }} optimized for performance and modern design standards.
            </p>
        </div>
    </header>

    <div class="filter-bar">
        <div style="color: var(--text-muted); font-weight: 600;">
            Showing <span style="color: white;">{{ $products->total() }}</span> assets
        </div>
        
        <form action="{{ request()->fullUrl() }}" method="GET" class="filter-group" x-data x-ref="filterForm">
            <select name="sort" class="filter-select" @change="$refs.filterForm.submit()">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Releases</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
            </select>

            <select name="type" class="filter-select" @change="$refs.filterForm.submit()">
                <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                <option value="free" {{ request('type') == 'free' ? 'selected' : '' }}>Free Only</option>
                <option value="premium" {{ request('type') == 'premium' ? 'selected' : '' }}>Premium Only</option>
            </select>
        </form>
    </div>

    <main style="max-width: 1400px; margin: 0 auto; padding: 0 2rem;">
        @if($products->count() > 0)
            <div class="product-grid" style="padding: 0; max-width: none;">
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product) }}" wire:navigate class="product-card" style="text-decoration: none; color: inherit;">
                        <div class="product-image-wrapper">
                            <img src="{{ $product->getPreviewUrl() }}" class="product-image" alt="{{ $product->title }}">
                            <div class="product-card-overlay">
                                <span class="btn btn-primary" style="padding: 0.6rem 1.5rem;">View Details</span>
                            </div>
                        </div>
                        <div class="product-info">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                                <span class="badge {{ $product->price_type?->value === 'free' ? 'badge-free' : 'badge-premium' }}">
                                    {{ $product->price_type?->label() ?? 'Free' }}
                                </span>
                                <div style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: var(--accent); font-weight: 700;">
                                    <i class="fa-solid fa-star"></i>
                                    <span>{{ number_format($product->average_rating, 1) }}</span>
                                </div>
                            </div>
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; font-weight: 800;">{{ $product->title }}</h3>
                            <div style="display: flex; gap: 1rem; color: var(--text-muted); font-size: 0.8rem;">
                                <span><i class="fa-solid fa-eye" style="margin-right: 0.3rem;"></i> {{ number_format($product->views_count) }}</span>
                                <span><i class="fa-solid fa-download" style="margin-right: 0.3rem;"></i> {{ number_format($product->downloads_count) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 4rem; display: flex; justify-content: center;">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 10rem 2rem;">
                <i class="fa-solid fa-box-open" style="font-size: 4rem; color: var(--glass-border); margin-bottom: 2rem;"></i>
                <h2 style="font-size: 2rem; margin-bottom: 1rem;">No assets found</h2>
                <p style="color: var(--text-muted);">We couldn't find any assets matching your filters in this category.</p>
                <a href="{{ url('/') }}" class="btn btn-primary" style="margin-top: 2rem;">Back to Marketplace</a>
            </div>
        @endif
    </main>

    @include('layouts.footer')
    
    @livewireScripts
    @livewire('activity-toast')
</body>
</html>
