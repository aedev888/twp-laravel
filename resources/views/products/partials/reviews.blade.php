<section id="reviews" style="padding-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <h2>User <span>Reviews</span> ({{ $product->reviews->count() }})</h2>
        <div style="display: flex; align-items: center; gap: 1rem; background: var(--card-bg); padding: 0.75rem 1.5rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
            <div style="display: flex; gap: 0.25rem;">
                @for($i=1; $i<=5; $i++)
                    <i class="fa-{{ $i <= round($product->average_rating) ? 'solid' : 'regular' }} fa-star" style="color: var(--accent);"></i>
                @endfor
            </div>
            <span style="font-weight: 700; font-size: 1.25rem;">{{ number_format($product->average_rating, 1) }}</span>
        </div>
    </div>

    @auth
        <div style="background: var(--card-bg); border: 1px solid var(--glass-border); border-radius: 2rem; padding: 2rem; margin-bottom: 4rem;">
            <h3 style="margin-bottom: 1.5rem;">Leave a <span>Review</span></h3>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Rating</label>
                    <div x-data="{ rating: 5, hover: 0 }" class="rating-input" style="display: flex; gap: 0.5rem;">
                        @for($i=1; $i<=5; $i++)
                            <label 
                                @mouseover="hover = {{ $i }}" 
                                @mouseleave="hover = 0" 
                                @click="rating = {{ $i }}"
                                style="cursor: pointer; transition: transform 0.2s ease;"
                                :style="{ transform: hover === {{ $i }} ? 'scale(1.2)' : 'scale(1)' }"
                            >
                                <input type="radio" name="rating" value="{{ $i }}" x-model="rating" style="display: none;">
                                <i class="fa-star" 
                                   :class="(hover || rating) >= {{ $i }} ? 'fa-solid' : 'fa-regular'"
                                   :style="{ color: (hover || rating) >= {{ $i }} ? 'var(--accent)' : 'var(--text-muted)' }"
                                   style="font-size: 1.75rem;"></i>
                            </label>
                        @endfor
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Your Feedback</label>
                    <textarea name="content" rows="4" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1rem; color: white; outline: none; transition: border-color 0.3s;" placeholder="What do you think about this asset?" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Post Review</button>
            </form>
        </div>
    @else
        <div style="text-align: center; padding: 3rem; background: var(--card-bg); border: 1px solid var(--glass-border); border-radius: 2rem; margin-bottom: 4rem;">
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">You must be logged in to leave a review.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Log In to Review</a>
        </div>
    @endauth

    <div class="reviews-list" style="display: flex; flex-direction: column; gap: 2rem;">
        @forelse($product->reviews as $review)
            <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 1.5rem; padding: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white;">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $review->user->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.2rem;">
                        @for($i=1; $i<=5; $i++)
                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star" style="color: var(--accent); font-size: 0.8rem;"></i>
                        @endfor
                    </div>
                </div>
                <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 1.5rem;">{{ $review->content }}</p>
                
                <div style="display: flex; justify-content: flex-end;">
                    @livewire('review-votes', ['review' => $review], key('review-'.$review->id))
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fa-regular fa-comment-dots" style="font-size: 3rem; color: var(--glass-border); margin-bottom: 1rem;"></i>
                <p style="color: var(--text-muted);">No reviews yet. Be the first to share your thoughts!</p>
            </div>
        @endforelse
    </div>
</section>
