<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    @php
        $seo = $post->seo ?? [];
        $geo = $post->geo_data ?? [];
        $seoTitle = ($seo['title'] ?? $post->title) . ' - Lumina Insights';
        $seoDesc = $seo['description'] ?? Str::limit(strip_tags($post->excerpt ?? $post->content), 160);
        $ogImage = $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/1200x800';
        $authorName = $post->author->name ?? 'Admin';
        
        // Prepare Article Schema
        $articleSchema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "headline" => $post->title,
            "description" => $geo['ai_summary'] ?? $seoDesc,
            "image" => $ogImage,
            "author" => [
                "@type" => "Person",
                "name" => $authorName
            ],
            "datePublished" => $post->published_at?->toIso8601String(),
            "publisher" => [
                "@type" => "Organization",
                "name" => "LuminaCMS",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => asset('images/logo.png')
                ]
            ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => request()->url()
            ],
            "keywords" => implode(', ', $geo['entities'] ?? [])
        ];

        // Prepare FAQ Schema
        $faqSchema = null;
        if (!empty($geo['faqs'])) {
            $faqSchema = [
                "@context" => "https://schema.org",
                "@type" => "FAQPage",
                "mainEntity" => array_map(function($faq) {
                    return [
                        "@type" => "Question",
                        "name" => $faq['question'],
                        "acceptedAnswer" => [
                            "@type" => "Answer",
                            "text" => $faq['answer']
                        ]
                    ];
                }, $geo['faqs'])
            ];
        }
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    
    <script type="application/ld+json">{!! json_encode($articleSchema) !!}</script>
    @if($faqSchema)
    <script type="application/ld+json">{!! json_encode($faqSchema) !!}</script>
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles

    <style>
        .post-content p { margin-bottom: 2rem; }
        .post-content h2 { font-size: 2.5rem; font-weight: 800; margin: 4rem 0 2rem; color: white; }
        .social-btn {
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: var(--transition);
            text-decoration: none;
        }
        .social-btn:hover {
            background: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--glow-shadow);
        }
        .prose img { max-width: 100%; border-radius: 1.5rem; margin: 2rem 0; }
    </style>
</head>
<body>
    @include('layouts.navbar')

    <article class="post-detail-container">
        <header class="post-header" style="padding: 180px 2rem 80px; background: radial-gradient(circle at 50% -20%, rgba(99, 102, 241, 0.15), transparent 70%); border-bottom: 1px solid var(--glass-border);">
            <div style="max-width: 900px; margin: 0 auto; text-align: center;">
                <div class="post-meta" style="display: flex; align-items: center; justify-content: center; gap: 1.5rem; margin-bottom: 2rem; color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">
                    <span style="color: var(--primary);">{{ $post->published_at?->format('F d, Y') }}</span>
                    <span>•</span>
                    <span>{{ $post->taxonomies->first()?->name ?? 'Article' }}</span>
                    <span>•</span>
                    <span>5 min read</span>
                </div>
                <h1 style="font-size: 4.5rem; font-weight: 950; letter-spacing: -0.04em; line-height: 1.1; margin-bottom: 2.5rem;">{{ $post->title }}</h1>
                <div class="post-author" style="display: flex; align-items: center; justify-content: center; gap: 1rem;">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name ?? 'Admin') }}&background=6366f1&color=fff" style="width: 48px; height: 48px; border-radius: 50%;">
                    <div style="text-align: left;">
                        <div style="font-weight: 800; font-size: 1.1rem;">{{ $post->author->name ?? 'Admin' }}</div>
                        <div style="color: var(--text-muted); font-size: 0.85rem;">Senior Software Architect</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="post-body-wrapper" style="max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: 1fr 350px; gap: 4rem; padding: 5rem 2rem;">
            <div class="post-main-content">
                <div class="featured-image-wrapper" style="margin-bottom: 4rem; border-radius: 2.5rem; overflow: hidden; border: 1px solid var(--glass-border); box-shadow: 0 40px 100px rgba(0,0,0,0.4);">
                    <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}" style="width: 100%; height: auto;">
                </div>

                @php
                    $takeaways = $geo['key_takeaways'] ?? [];
                    $faqs = $geo['faqs'] ?? [];
                @endphp

                @if(!empty($takeaways))
                <div class="key-takeaways" style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); border-radius: 1.5rem; padding: 2rem; margin-bottom: 3rem; backdrop-filter: blur(10px);">
                    <h3 style="margin-top: 0; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem;">
                        <i class="fa-solid fa-lightbulb" style="color: var(--primary);"></i>
                        Key Takeaways
                    </h3>
                    <ul style="margin-bottom: 0; padding-left: 1.5rem; color: rgba(255, 255, 255, 0.8);">
                        @foreach($takeaways as $item)
                            @if(!empty($item['point']))
                                <li style="margin-bottom: 0.5rem;">{{ $item['point'] }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="post-content prose prose-invert" style="font-size: 1.25rem; line-height: 1.8; color: rgba(255, 255, 255, 0.9);">
                    {!! Str::markdown($post->content ?? '') !!}
                </div>

                @if(!empty($faqs))
                <div class="post-faqs" style="margin-top: 5rem;">
                    <h3 style="font-size: 2rem; margin-bottom: 2rem;">Frequently Asked Questions</h3>
                    <div class="faq-grid" style="display: grid; gap: 1.5rem;">
                        @foreach($faqs as $faq)
                            @if(!empty($faq['question']) && !empty($faq['answer']))
                            <div class="faq-item" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
                                <h4 style="margin: 0 0 1rem 0; color: #fff;">{{ $faq['question'] }}</h4>
                                <p style="margin: 0; color: rgba(255, 255, 255, 0.7);">{{ $faq['answer'] }}</p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="post-tags" style="margin-top: 5rem; padding-top: 3rem; border-top: 1px solid var(--glass-border); display: flex; gap: 0.75rem;">
                    @foreach($post->taxonomies as $tag)
                        <a href="#" class="btn btn-secondary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 99px; text-decoration: none; color: inherit;">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>

            <aside class="post-sidebar">
                <div class="sidebar-widget" style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 2rem; padding: 2.5rem; position: sticky; top: 120px;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem;">Newsletter</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 2rem;">Get the latest insights delivered straight to your inbox.</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="your@email.com" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 99px; padding: 0.75rem 1.5rem; color: white; outline: none; margin-bottom: 1rem;">
                        <button class="btn btn-primary" style="width: 100%;">Subscribe</button>
                    </form>

                    <div style="margin-top: 3rem;">
                        <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem;">Share Article</h3>
                        <div style="display: flex; gap: 1rem;">
                            <a href="#" class="social-btn"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="#" class="social-btn"><i class="fa-brands fa-linkedin"></i></a>
                            <a href="#" class="social-btn"><i class="fa-brands fa-facebook"></i></a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </article>

    @include('layouts.footer')
    @livewireScripts
</body>
</html>
