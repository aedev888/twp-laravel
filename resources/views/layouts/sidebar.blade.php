<aside class="left-sidebar">
    <div class="sidebar-nav-group">
        <h3 class="sidebar-nav-title">Main Menu</h3>
        <a href="/" class="sidebar-nav-item active">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="sidebar-nav-item">
            <i class="fa-solid fa-layer-group"></i>
            <span>Full Catalog</span>
        </a>
    </div>

    <div class="sidebar-nav-group">
        <h3 class="sidebar-nav-title">Categories</h3>
        @foreach($categories as $category)
            <a href="{{ route('categories.show', $category) }}" class="sidebar-nav-item">
                <i class="fa-brands fa-wordpress"></i> <!-- Placeholder icon -->
                <span>{{ $category->name }}</span>
            </a>
        @endforeach
    </div>

    <div class="sidebar-nav-group">
        <h3 class="sidebar-nav-title">Community</h3>
        <a href="{{ route('blog.index') }}" class="sidebar-nav-item">
            <i class="fa-solid fa-newspaper"></i>
            <span>News / Blog</span>
        </a>
        <a href="#" class="sidebar-nav-item">
            <i class="fa-solid fa-circle-question"></i>
            <span>Help Center</span>
        </a>
    </div>

    @if(isset($latestReviews) && $latestReviews->count() > 0)
        <div class="reviews-sidebar-block">
            <h3 class="reviews-sidebar-title">Recent Reviews</h3>
            <div class="reviews-list">
                @foreach($latestReviews as $review)
                    <div class="review-sidebar-item">
                        <div class="review-sidebar-user">
                            <div class="review-user-info">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=6366f1&color=fff" class="review-user-avatar">
                                <span class="review-username">{{ $review->user->name }}</span>
                            </div>
                            <span class="review-date">{{ $review->created_at->format('d.m.Y - H:i') }}</span>
                        </div>
                        <div class="review-product-name">{{ $review->product->title }}</div>
                        <div class="review-text">
                            "{{ Str::limit($review->content, 60) }}"
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('reviews.index') }}" class="btn-view-all-reviews">View All</a>
        </div>
    @endif
</aside>
