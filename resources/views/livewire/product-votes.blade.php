<div class="product-votes-container">
    <div class="vote-progress-wrapper">
        <div class="vote-percentage-label">{{ $product->vote_percentage }}%</div>
        <div class="vote-progress-bar">
            <div class="vote-progress-fill" style="width: {{ $product->vote_percentage }}%"></div>
        </div>
    </div>

    <div class="vote-buttons">
        <button wire:click="vote('like')" class="vote-btn like-btn {{ auth()->check() && $product->votes()->where('user_id', auth()->id())->where('type', 'like')->exists() ? 'active' : '' }}">
            <div class="vote-count-badge">{{ $product->likes_count }}</div>
            <span class="vote-label">Like</span>
            <i class="fa-solid fa-thumbs-up"></i>
        </button>

        <button wire:click="vote('dislike')" class="vote-btn dislike-btn {{ auth()->check() && $product->votes()->where('user_id', auth()->id())->where('type', 'dislike')->exists() ? 'active' : '' }}">
            <i class="fa-solid fa-thumbs-down"></i>
            <span class="vote-label">Dislike</span>
            <div class="vote-count-badge">{{ $product->dislikes_count }}</div>
        </button>
    </div>

    <style>
        .product-votes-container {
            margin: 2rem 0;
            width: 100%;
        }
        .vote-progress-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }
        .vote-percentage-label {
            position: absolute;
            top: -1.5rem;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.85rem;
            font-weight: 800;
            color: #10b981;
        }
        .vote-progress-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 99px;
            overflow: hidden;
            position: relative;
        }
        .vote-progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ef4444;
            opacity: {{ ($product->likes_count + $product->dislikes_count) > 0 ? 1 : 0 }};
            z-index: 0;
        }
        .vote-progress-fill {
            height: 100%;
            background: #10b981;
            transition: width 0.5s ease;
            position: relative;
            z-index: 1;
        }
        .vote-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .vote-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1.25rem;
            border-radius: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            color: white;
            font-weight: 700;
        }
        .like-btn {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .like-btn:hover {
            background: rgba(16, 185, 129, 0.2);
        }
        .like-btn.active {
            background: #10b981;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        }
        .dislike-btn {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .dislike-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }
        .dislike-btn.active {
            background: #ef4444;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
        }
        .vote-count-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
        }
        .vote-label {
            flex-grow: 1;
            text-align: center;
        }
    </style>
</div>
