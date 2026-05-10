<div class="review-votes-wrapper">
    <button wire:click="vote('dislike')" class="review-vote-btn dislike {{ auth()->check() && $review->votes()->where('user_id', auth()->id())->where('type', 'dislike')->exists() ? 'active' : '' }}">
        <i class="fa-solid fa-thumbs-down"></i>
    </button>
    
    <span class="review-vote-count {{ ($review->likes_count - $review->dislikes_count) >= 0 ? 'positive' : 'negative' }}">
        {{ ($review->likes_count - $review->dislikes_count) > 0 ? '+' : '' }}{{ $review->likes_count - $review->dislikes_count }}
    </span>

    <button wire:click="vote('like')" class="review-vote-btn like {{ auth()->check() && $review->votes()->where('user_id', auth()->id())->where('type', 'like')->exists() ? 'active' : '' }}">
        <i class="fa-solid fa-thumbs-up"></i>
    </button>

    <style>
        .review-votes-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.4rem 0.6rem;
            border-radius: 0.75rem;
            border: 1px solid var(--glass-border);
        }
        .review-vote-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .review-vote-btn:hover {
            color: white;
        }
        .review-vote-btn.like.active {
            color: #10b981;
        }
        .review-vote-btn.dislike.active {
            color: #ef4444;
        }
        .review-vote-count {
            font-weight: 800;
            font-size: 0.85rem;
            min-width: 1.5rem;
            text-align: center;
        }
        .review-vote-count.positive { color: #10b981; }
        .review-vote-count.negative { color: #ef4444; }
    </style>
</div>
