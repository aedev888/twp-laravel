<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lumina Insights - Professional Blog</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    @include('layouts.navbar')

    <main style="padding: 120px 2rem 60px;">
        <div class="section-header" style="text-align: center; margin-bottom: 4rem;">
            <h1 style="font-size: 4rem; font-weight: 950; letter-spacing: -0.04em;">Lumina <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Insights</span></h1>
            <p style="color: var(--text-muted); font-size: 1.25rem; max-width: 600px; margin: 1.5rem auto 0;">Stay ahead with the latest news, tutorials, and insights from the Lumina Marketplace ecosystem.</p>
        </div>

        <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2.5rem; max-width: 1400px; margin: 0 auto;">
            @foreach($posts as $post)
                <article class="post-card premium-card" onclick="window.location='{{ $post->getUrl() }}'" style="cursor: pointer;">
                    <div class="post-image-wrapper" style="aspect-ratio: 16/9; overflow: hidden; position: relative; border-radius: 1.25rem 1.25rem 0 0;">
                        <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;">
                        <div class="post-date-badge" style="position: absolute; top: 1.25rem; left: 1.25rem; background: rgba(10, 10, 12, 0.7); backdrop-filter: blur(8px); padding: 0.5rem 1rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; border: 1px solid var(--glass-border);">
                            {{ $post->published_at?->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="post-content" style="padding: 2rem;">
                        <div class="post-categories" style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                            @foreach($post->taxonomies as $taxonomy)
                                <span class="badge badge-primary" style="font-size: 0.65rem; padding: 0.3rem 0.6rem;">{{ $taxonomy->name }}</span>
                            @endforeach
                        </div>
                        <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.3;">{{ $post->title }}</h2>
                        <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 2rem;">{{ Str::limit($post->excerpt, 120) }}</p>
                        
                        <div class="post-footer" style="display: flex; align-items: center; justify-content: space-between; padding-top: 1.5rem; border-top: 1px solid var(--glass-border);">
                            <div class="post-author" style="display: flex; align-items: center; gap: 0.75rem;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=6366f1&color=fff" style="width: 32px; height: 32px; border-radius: 50%;">
                                <span style="font-size: 0.85rem; font-weight: 600;">{{ $post->author->name }}</span>
                            </div>
                            <span style="font-size: 0.85rem; color: var(--primary); font-weight: 700;">Read More <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper" style="margin-top: 6rem; display: flex; justify-content: center;">
            {{ $posts->links() }}
        </div>
    </main>

    @include('layouts.footer')

    <style>
        .post-card:hover img { transform: scale(1.05); }
        .post-card { transition: var(--transition); }
        .post-card:hover { transform: translateY(-10px); }
    </style>
    
    @livewireScripts
</body>
</html>
