<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags -->
    @php
        $seo = $product->seo ?? [];
        $seoTitle = ($seo['title'] ?? $product->title) . ' - Lumina Marketplace';
        $seoDesc = $seo['description'] ?? Str::limit(strip_tags($product->description), 160);
        $seoKeywords = $seo['keywords'] ?? '';
        $ogImage = isset($seo['og_image']) ? asset('storage/' . $seo['og_image']) : $product->getPreviewUrl();
        $isNoIndex = $seo['is_noindex'] ?? false;
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    @if($seoKeywords) <meta name="keywords" content="{{ $seoKeywords }}"> @endif
    <link rel="canonical" href="{{ $seo['canonical_url'] ?? url()->current() }}">
    
    @if($isNoIndex)
        <meta name="robots" content="noindex, nofollow">
    @endif

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $seo['og_title'] ?? $seoTitle }}">
    <meta property="og:description" content="{{ $seo['og_description'] ?? $seoDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['og_title'] ?? $seoTitle }}">
    <meta name="twitter:description" content="{{ $seo['og_description'] ?? $seoDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles
    
    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
        {!! json_encode($product->getSchemaData()) !!}
    </script>

    <style>
        .product-layout {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 3rem;
            padding-bottom: 8rem;
        }

        @media (max-width: 1024px) {
            .product-layout { grid-template-columns: 1fr; }
        }

        .breadcrumb-nav {
            margin-bottom: 2rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .breadcrumb-nav a { color: var(--text-muted); text-decoration: none; }
        .breadcrumb-nav span { margin: 0 0.5rem; }

        .main-preview {
            width: 100%;
            border-radius: 2rem;
            border: 1px solid var(--glass-border);
            overflow: hidden;
            margin-bottom: 2rem;
            background: #1e293b;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .main-preview img { width: 100%; display: block; }

        .content-section {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            padding: 2.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .content-section h2 { margin-bottom: 1.5rem; color: var(--primary); }

        .sidebar-sticky {
            position: sticky;
            top: 120px;
        }

        .cta-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            padding: 2.5rem;
            text-align: center;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }

        .price-tag {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 2rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .specs-table {
            width: 100%;
            margin-top: 1rem;
            border-collapse: collapse;
        }

        .specs-table td {
            padding: 1rem 0;
            border-bottom: 1px solid var(--glass-border);
            font-size: 0.875rem;
        }

        .specs-table td:first-child { color: var(--text-muted); width: 45%; }
        .specs-table td:last-child { text-align: right; font-weight: 700; color: white; }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 2rem;
            margin-top: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--glass-border);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 3rem;
        }

        .timeline-dot {
            position: absolute;
            left: -2.4rem;
            top: 0.25rem;
            width: 12px;
            height: 12px;
            background: var(--primary);
            border-radius: 50%;
            border: 3px solid #0a0a0c;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* FAQ Styles */
        .faq-item {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            border-radius: 1.25rem;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: 0.3s;
        }

        .faq-item:hover {
            background: rgba(255,255,255,0.04);
            border-color: var(--primary);
        }

        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
        }

        .faq-answer {
            padding: 0 1.5rem 1.5rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .wishlist-btn, .collection-btn {
            width: 100%; 
            padding: 1rem; 
            background: rgba(255,255,255,0.05); 
            border: 1px solid var(--glass-border); 
            border-radius: 99px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 0.75rem; 
            font-weight: 600; 
            color: white; 
            transition: 0.3s; 
            margin-top: 1rem;
            cursor: pointer;
        }

        .wishlist-btn:hover, .collection-btn:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(15px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modal-content {
            background: #0f0f12;
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            position: relative;
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.5);
        }

        .collection-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            border-radius: 1.25rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .collection-list-item:hover {
            background: rgba(255,255,255,0.05);
            border-color: var(--primary);
        }

        /* Sticky Buy Bar */
        .sticky-buy-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--glass-border);
            padding: 1rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transform: translateY(100%);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sticky-buy-bar.active {
            transform: translateY(0);
        }

        .tab-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.1rem;
            font-weight: 700;
            padding: 1rem 0;
            cursor: pointer;
            transition: 0.3s;
            position: relative;
        }

        .tab-btn.active-tab {
            color: white;
        }

        .tab-btn.active-tab::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-gradient);
        }
    </style>
</head>
<body x-data="{ openCollectionModal: false, showStickyBar: false }" @scroll.window="showStickyBar = (window.pageYOffset > 800)">
    <div class="sticky-buy-bar" :class="{ 'active': showStickyBar }">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <img src="{{ $product->getPreviewUrl() }}" style="width: 50px; height: 50px; border-radius: 0.75rem; object-fit: cover;">
            <div>
                <div style="font-weight: 800; color: white;">{{ $product->title }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $product->version }} • {{ $product->taxonomies->first()->name ?? 'Asset' }}</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 2rem;">
            <div style="font-size: 1.5rem; font-weight: 900; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ $product->price_type?->value === 'free' ? 'Free' : 'Premium' }}
            </div>
            <a href="{{ route('products.download', $product) }}" class="btn btn-primary" style="padding: 0.75rem 2.5rem; border-radius: 99px;">
                Download Now
            </a>
        </div>
    </div>

    @include('layouts.navbar')

    <main class="dashboard-container" style="margin-top: 120px; max-width: 1300px;">
        <div class="product-layout">
            <section class="product-main">
                <nav class="breadcrumb-nav">
                    <a href="{{ route('products.index') }}" wire:navigate>Marketplace</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    @if($product->taxonomies->first())
                        <a href="{{ route('categories.show', $product->taxonomies->first()) }}" wire:navigate>{{ $product->taxonomies->first()->name }}</a>
                        <i class="fa-solid fa-chevron-right"></i>
                    @endif
                    <span style="color: white; font-weight: 500;">{{ Str::limit($product->title, 40) }}</span>
                </nav>

            <h1 style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1rem; letter-spacing: -0.03em;">{{ $product->title }}</h1>
            
            <div style="display: flex; gap: 2rem; margin-bottom: 2.5rem; color: var(--text-muted); font-size: 0.9rem; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-eye" style="color: var(--primary);"></i>
                    <span><b>{{ number_format($product->views_count) }}</b> views</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-download" style="color: var(--accent);"></i>
                    <span><b>{{ number_format($product->downloads_count) }}</b> downloads</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-star" style="color: #f59e0b;"></i>
                    <span><b>{{ number_format($product->average_rating, 1) }}</b> rating</span>
                </div>
                <div style="margin-left: auto; display: flex; gap: 1rem; align-items: center;">
                    <span style="font-weight: 600;">Share:</span>
                    <a href="https://t.me/share/url?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($product->title) }}" target="_blank" style="color: inherit; transition: 0.3s;" onmouseover="this.style.color='#0088cc'" onmouseout="this.style.color='inherit'"><i class="fa-brands fa-telegram"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($product->title) }}" target="_blank" style="color: inherit; transition: 0.3s;" onmouseover="this.style.color='#1da1f2'" onmouseout="this.style.color='inherit'"><i class="fa-brands fa-x-twitter"></i></a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied!')" style="background: none; border: none; color: inherit; cursor: pointer; transition: 0.3s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='inherit'"><i class="fa-solid fa-link"></i></button>
                </div>
            </div>
            
            <div class="main-preview">
                <img src="{{ $product->getPreviewUrl() }}" alt="{{ $product->title }}">
            </div>

            <!-- Content Tabs -->
            <div x-data="{ tab: 'overview' }" style="margin-top: 4rem;">
                <div style="display: flex; gap: 3rem; border-bottom: 1px solid var(--glass-border); margin-bottom: 3rem; padding-bottom: 0.5rem; overflow-x: auto;">
                    <button @click="tab = 'overview'" :class="tab === 'overview' ? 'active-tab' : ''" class="tab-btn">Overview</button>
                    <button @click="tab = 'changelog'" :class="tab === 'changelog' ? 'active-tab' : ''" class="tab-btn">Changelog</button>
                    <button @click="tab = 'faq'" :class="tab === 'faq' ? 'active-tab' : ''" class="tab-btn">FAQ</button>
                    <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'active-tab' : ''" class="tab-btn">Reviews ({{ $product->reviews->count() }})</button>
                </div>

                <div x-show="tab === 'overview'" x-transition:enter="fade-in">
                    <div class="content-section">
                        <h2>Product Overview</h2>
                        <div style="font-size: 1.125rem; color: var(--text-muted); line-height: 1.8;">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'changelog'" x-transition:enter="fade-in" style="display: none;">
                    <div class="content-section">
                        <h2>Update <span>History</span></h2>
                        @if($product->changelog && count($product->changelog) > 0)
                            <div class="timeline">
                                @foreach($product->changelog as $entry)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                            <span style="background: var(--primary-gradient); color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: 800; font-size: 0.8rem;">v{{ $entry['version'] }}</span>
                                            <span style="color: var(--text-muted); font-size: 0.875rem;">{{ \Carbon\Carbon::parse($entry['release_date'] ?? now())->format('M d, Y') }}</span>
                                        </div>
                                        <ul style="list-style: none; padding-left: 0; color: var(--text-muted);">
                                            @foreach((array)($entry['changes'] ?? []) as $change)
                                                <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
                                                    <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 0.8rem;"></i>
                                                    {{ $change }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="color: var(--text-muted); text-align: center; padding: 4rem;">No changelog available.</p>
                        @endif
                    </div>
                </div>

                <div x-show="tab === 'faq'" x-transition:enter="fade-in" style="display: none;">
                    <div class="content-section">
                        <h2>Frequently Asked <span>Questions</span></h2>
                        @if($product->faq && count($product->faq) > 0)
                            <div x-data="{ active: null }">
                                @foreach($product->faq as $index => $item)
                                    <div class="faq-item">
                                        <div class="faq-question" @click="active = (active === {{ $index }} ? null : {{ $index }})">
                                            {{ $item['question'] }}
                                            <i class="fa-solid" :class="active === {{ $index }} ? 'fa-minus' : 'fa-plus'"></i>
                                        </div>
                                        <div class="faq-answer" x-show="active === {{ $index }}" x-collapse>
                                            {{ $item['answer'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="color: var(--text-muted); text-align: center; padding: 4rem;">No FAQ items yet.</p>
                        @endif
                    </div>
                </div>

                <div x-show="tab === 'reviews'" x-transition:enter="fade-in" style="display: none;">
                    @include('products.partials.reviews', ['product' => $product])
                </div>
            </div>

            @if($relatedProducts->count() > 0)
                <h2 style="margin: 4rem 0 2rem;">Related <span>Assets</span></h2>
                <div class="product-grid" style="padding: 0; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('products.show', $related) }}" wire:navigate class="product-card" style="text-decoration: none; color: inherit;">
                            <div class="product-image-wrapper">
                                <img src="{{ $related->getPreviewUrl() }}" class="product-image" alt="{{ $related->title }}">
                                <div class="product-card-overlay" style="flex-direction: column; gap: 0.5rem;">
                                    <span class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem;">View</span>
                                </div>
                            </div>
                            <div class="product-info" style="padding: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <i class="fa-solid fa-star" style="color: var(--accent); font-size: 0.7rem;"></i>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($related->average_rating, 1) }}</span>
                                </div>
                                <h3 style="font-size: 1rem; margin: 0;">{{ Str::limit($related->title, 40) }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="product-sidebar">
            <div class="sidebar-sticky">
                <div class="cta-box">
                    <div style="margin-bottom: 2rem; text-align: left;">
                        <h4 style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 1.25rem; font-weight: 800;">Compatibility</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                            <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(16, 185, 129, 0.2); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-check-double"></i> WP 6.5+
                            </span>
                            <span style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(99, 102, 241, 0.2); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-microchip"></i> PHP 8.2+
                            </span>
                        </div>
                    </div>
                    
                    <div class="price-tag">
                        @if($product->price_type?->value === 'free')
                            Free
                        @else
                            Premium
                        @endif
                    </div>
                    
                    <a href="{{ route('products.download', $product) }}" class="btn btn-primary" style="width: 100%; padding: 1.25rem; font-size: 1.1rem; font-weight: 800; border-radius: 99px; box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.4);">
                        <i class="fa-solid fa-download" style="margin-right: 0.75rem;"></i> Download Now
                    </a>

                    @livewire('product-votes', ['product' => $product])


                    @auth
                        <button @click="openCollectionModal = true" class="collection-btn">
                            <i class="fa-solid fa-folder-plus"></i> Save to Collection
                        </button>

                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="wishlist-btn">
                                @if(auth()->user()->wishlist()->where('product_id', $product->id)->exists())
                                    <i class="fa-solid fa-heart" style="color: #ef4444;"></i> Saved to Wishlist
                                @else
                                    <i class="fa-regular fa-heart"></i> Add to Wishlist
                                @endif
                            </button>
                        </form>
                    @endauth
                </div>

                <div class="content-section" style="padding: 2rem;">
                    <table class="specs-table">
                        <tr>
                            <td>Version</td>
                            <td>{{ $product->version ?? '1.0.0' }}</td>
                        </tr>
                        <tr>
                            <td>Last Updated</td>
                            <td>{{ $product->updated_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td>Downloads</td>
                            <td>{{ number_format($product->logs_count ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>License</td>
                            <td>GPL / Standard</td>
                        </tr>
                    </table>
                </div>
            </div>
        </aside>
    </div>
    </main>

    <!-- Collection Selection Modal -->
    @auth
    <template x-if="openCollectionModal">
        <div class="modal-overlay" @click.self="openCollectionModal = false">
            <div class="modal-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 900;">Add to <span>Collection</span></h2>
                    <button @click="openCollectionModal = false" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem;">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <div style="max-height: 400px; overflow-y: auto; padding-right: 0.5rem;">
                    @php
                        $userCollections = auth()->user()->collections()->with('products')->get();
                    @endphp
                    
                    @forelse($userCollections as $collection)
                        <form action="{{ route('dashboard.collections.toggle', [$collection, $product]) }}" method="POST">
                            @csrf
                            <button type="submit" class="collection-list-item">
                                <span style="font-weight: 700; color: white;">{{ $collection->name }}</span>
                                @if($collection->products->contains($product->id))
                                    <i class="fa-solid fa-check-circle" style="color: #10b981;"></i>
                                @else
                                    <i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i>
                                @endif
                            </button>
                        </form>
                    @empty
                        <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <p style="margin-bottom: 1.5rem;">You don't have any collections yet.</p>
                            <a href="{{ route('dashboard', ['tab' => 'collections']) }}" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.6rem 1.2rem;">Create First Collection</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </template>
    @endauth

    @include('layouts.footer')
    @livewireScripts
    @livewire('activity-toast')
</body>
</html>
