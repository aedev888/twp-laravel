<aside class="right-sidebar">
    <div class="sidebar-nav-group">
        <h3 class="sidebar-nav-title">Daily Trending</h3>
        <div class="popular-list">
            @foreach($popularAssets as $popular)
                <a href="{{ route('products.show', $popular) }}" class="popular-item">
                    <div class="popular-icon">
                        <i class="fa-brands fa-wordpress" style="color: var(--primary);"></i>
                    </div>
                    <div class="popular-details">
                        <div class="popular-title">{{ Str::limit($popular->title, 40) }}</div>
                        <div class="popular-meta">
                            <i class="fa-solid fa-eye"></i> {{ number_format($popular->views_count) }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="sidebar-nav-group" style="margin-top: 3rem;">
        <h3 class="sidebar-nav-title">Marketplace Stats</h3>
        <div style="background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid var(--glass-border);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span style="color: var(--text-muted); font-size: 0.8rem;">Total Assets</span>
                <span style="font-weight: 700;">{{ $stats['products'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted); font-size: 0.8rem;">Downloads</span>
                <span style="font-weight: 700;">{{ $stats['downloads'] }}</span>
            </div>
        </div>
    </div>
</aside>
