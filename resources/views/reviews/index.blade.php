<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Community Reviews - Lumina Marketplace</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles
</head>
<body>
    @include('layouts.navbar')

    <main class="marketplace-layout">
        <!-- Левый сайдбар -->
        @include('layouts.sidebar')

        <!-- Центр: Контент -->
        <div class="main-content" style="padding: 2rem;">
            <div style="margin-bottom: 3rem;">
                <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 0.5rem; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Community Feedback
                </h1>
                <p style="color: var(--text-muted); font-size: 1.1rem;">See what our community thinks about the latest digital assets.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 2rem;">
                @foreach($reviews as $review)
                    <div class="review-card-full">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=6366f1&color=fff" style="width: 48px; height: 48px; border-radius: 50%;">
                                <div>
                                    <div style="font-weight: 800; font-size: 1rem;">{{ $review->user->name }}</div>
                                    <div style="color: var(--primary); font-size: 0.8rem;">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fa-{{ $i < $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $review->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: rgba(255,255,255,0.02); border-radius: 1rem; border-left: 4px solid var(--primary);">
                            <p style="font-size: 0.95rem; line-height: 1.6; font-style: italic; color: rgba(255,255,255,0.9);">
                                "{{ $review->content }}"
                            </p>
                        </div>

                        <a href="{{ route('products.show', $review->product) }}" class="review-product-link">
                            <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-brands fa-wordpress"></i>
                            </div>
                            <div style="flex-grow: 1;">
                                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Reviewing</div>
                                <div style="font-weight: 700; color: white;">{{ $review->product->title }}</div>
                            </div>
                            @livewire('review-votes', ['review' => $review], key('review-index-'.$review->id))
                        </a>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 4rem;">
                {{ $reviews->links() }}
            </div>
        </div>

        <!-- Правый сайдбар -->
        @include('layouts.right-sidebar')
    </main>

    <style>
        .review-card-full {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 2rem;
            transition: var(--transition);
        }
        .review-card-full:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        .review-product-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            padding: 1rem;
            background: rgba(255,255,255,0.02);
            border-radius: 1.25rem;
            border: 1px solid transparent;
            transition: var(--transition);
        }
        .review-product-link:hover {
            background: rgba(255,255,255,0.05);
            border-color: var(--glass-border);
        }
    </style>

    @include('layouts.footer')
    @livewireScripts
    @livewire('activity-toast')
</body>
</html>
