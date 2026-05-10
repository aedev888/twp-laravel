<div class="search-wrapper" x-data="{ open: @entangle('showDropdown') }" @click.away="open = false">
    <div class="search-input-group">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input 
            wire:model.live.debounce.300ms="query" 
            type="text" 
            placeholder="Search premium assets..." 
            class="search-input"
            @focus="if($wire.query.length >= 2) open = true"
            @keydown.escape="open = false"
        >
        <div wire:loading class="search-loader">
            <div class="spinner"></div>
        </div>
    </div>

    <div 
        x-show="open" 
        x-transition:enter="dropdown-enter"
        x-transition:leave="dropdown-leave"
        class="search-dropdown"
        style="display: none;"
    >
        @if($results->count() > 0)
            <div class="dropdown-header">Top Results</div>
            <div class="dropdown-results">
                @foreach($results as $product)
                    <a href="{{ route('products.show', $product) }}" class="search-result-item">
                        <img src="{{ $product->getPreviewUrl() }}" alt="{{ $product->title }}" class="result-thumb">
                        <div class="result-info">
                            <div class="result-title">{{ $product->title }}</div>
                            <div class="result-meta">
                                <span class="result-category">{{ $product->taxonomies->first()->name ?? 'Asset' }}</span>
                                <span class="result-divider">•</span>
                                <span class="result-rating">
                                    <i class="fa-solid fa-star"></i> {{ number_format($product->average_rating, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="result-price {{ $product->price_type?->value }}">
                            {{ $product->price_type?->value === 'free' ? 'Free' : '$' . ($product->price ?? '0') }}
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="dropdown-footer">
                Press Enter to see all results for "{{ $query }}"
            </div>
        @else
            <div class="no-results">
                <i class="fa-solid fa-ghost"></i>
                <p>No assets found for "{{ $query }}"</p>
            </div>
        @endif
    </div>

    <style>
        .search-wrapper {
            position: relative;
            width: 100%;
            max-width: 600px;
        }

        .search-input-group {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 1.25rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-input-group:focus-within {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
            transform: translateY(-1px);
        }

        .search-icon {
            color: var(--text-muted);
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .search-input {
            background: transparent;
            border: none;
            color: white;
            width: 100%;
            padding: 0.5rem 0;
            font-size: 1rem;
            font-family: inherit;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .search-dropdown {
            position: absolute;
            top: calc(100% + 1rem);
            left: 0;
            right: 0;
            background: rgba(13, 13, 18, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
        }

        .dropdown-header {
            padding: 1.25rem 1.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
        }

        .search-result-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
            gap: 1rem;
        }

        .search-result-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .result-thumb {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            object-fit: cover;
            border: 1px solid var(--glass-border);
        }

        .result-info {
            flex: 1;
        }

        .result-title {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            color: white;
        }

        .result-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .result-rating i {
            color: #f59e0b;
        }

        .result-price {
            font-weight: 800;
            font-size: 0.9rem;
        }

        .result-price.free { color: #10b981; }
        .result-price.premium { color: var(--primary); }

        .dropdown-footer {
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border-top: 1px solid var(--glass-border);
            font-size: 0.75rem;
            color: var(--text-muted);
            text-align: center;
        }

        .no-results {
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--text-muted);
        }

        .no-results i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .search-loader {
            margin-left: 1rem;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.1);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .dropdown-enter { opacity: 0; transform: translateY(10px); }
        .dropdown-leave { opacity: 0; transform: translateY(10px); }
    </style>
</div>
