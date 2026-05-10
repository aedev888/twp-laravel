<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lumina Marketplace - Premium WP Themes & Plugins</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles
    
    <style>
        .hero {
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.9)), 
                        url('{{ asset('images/hero-bg.png') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    @include('layouts.navbar')

    <main class="marketplace-layout">
        <!-- Левый сайдбар -->
        @include('layouts.sidebar')

        <!-- Центр: Контент -->
        <div class="main-content">
            <!-- Верхняя сетка категорий -->
            <div class="category-cards-grid">
                <a href="{{ url('/') }}" class="category-card">
                    <div class="category-icon" style="color: var(--primary);"><i class="fa-solid fa-layer-group"></i></div>
                    <h4>Catalog</h4>
                    <span class="category-count">{{ $stats['products'] ?? '0' }}</span>
                </a>
                <a href="{{ route('categories.show', 'themes') }}" class="category-card">
                    <div class="category-icon" style="color: #21759b;"><i class="fa-brands fa-wordpress"></i></div>
                    <h4>WordPress</h4>
                    <span class="category-count">5,845</span>
                </a>
                <a href="{{ route('categories.show', 'plugins') }}" class="category-card">
                    <div class="category-icon" style="color: #96588a;"><i class="fa-brands fa-shopify"></i></div>
                    <h4>WooCommerce</h4>
                    <span class="category-count">2,475</span>
                </a>
                <a href="{{ route('categories.show', 'plugins') }}" class="category-card">
                    <div class="category-icon" style="color: #f59e0b;"><i class="fa-solid fa-code"></i></div>
                    <h4>PHP Scripts</h4>
                    <span class="category-count">1,850</span>
                </a>
                <a href="{{ route('categories.show', 'themes') }}" class="category-card">
                    <div class="category-icon" style="color: #ef4444;"><i class="fa-brands fa-magento"></i></div>
                    <h4>Magento</h4>
                    <span class="category-count">312</span>
                </a>
            </div>

            <!-- View Modes and Header -->
            <div class="section-header-wrapper">
                <div class="section-title-group">
                    <h2 class="section-heading">Latest Releases & Updates</h2>
                    <div class="view-switch desktop-only">
                        <div class="view-btn active"><i class="fa-solid fa-table-cells-large"></i></div>
                        <div class="view-btn"><i class="fa-solid fa-list"></i></div>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-primary view-all-btn">
                    View Full Catalog <span class="catalog-count-badge">{{ $stats['products'] ?? '0' }}</span>
                </a>
            </div>

            <!-- Сетка товаров -->
            <div class="product-grid">
                @foreach($featuredProducts as $product)
                    <div class="product-card premium-card" onclick="window.location='{{ route('products.show', $product) }}'">
                        <div class="product-image-wrapper">
                            <img src="{{ $product->getPreviewUrl() }}" class="product-image" alt="{{ $product->title }}">
                            <div class="card-platform-icon">
                                <i class="fa-brands fa-wordpress"></i>
                            </div>
                            <div class="product-card-badge">
                                <span class="badge {{ $product->price_type?->value === 'free' ? 'badge-free' : 'badge-premium' }}">
                                    {{ $product->price_type?->value === 'free' ? 'Free' : 'Nulled' }}
                                </span>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem; height: 3rem; overflow: hidden; line-height: 1.5;">{{ $product->title }}</h3>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--text-muted);">
                                <span style="color: var(--primary); font-weight: 700;">{{ $product->version ?? '1.0.0' }}</span>
                                <span>•</span>
                                <span>{{ $product->taxonomies->first()?->name ?? 'Plugin' }}</span>
                            </div>
                        </div>
                        <div class="card-meta-bar">
                            <div class="card-meta-item">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>{{ $product->published_at?->format('d.m.Y') ?? now()->format('d.m.Y') }}</span>
                            </div>
                            <div class="card-meta-item" style="justify-content: center;">
                                <i class="fa-solid fa-eye"></i>
                                <span>{{ number_format($product->views_count) }}</span>
                            </div>
                            <div class="card-meta-item" style="justify-content: flex-end;">
                                <i class="fa-solid fa-comment"></i>
                                <span>{{ $product->reviews_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- Правый сайдбар -->
        @include('layouts.right-sidebar')
    </main>

    <style>
        .review-card-premium {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        .review-card-premium:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
    </style>

    @include('layouts.footer')

    @livewireScripts
    @livewire('activity-toast')
</body>
</html>
