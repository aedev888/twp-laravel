<?php

$post = new \App\Models\Post();
$post->title = 'Generative Engine Optimization with Laravel';
$post->slug = 'geo-laravel';
$post->excerpt = 'Learn how to optimize your Laravel application for AI search engines like ChatGPT and Perplexity.';
$post->content = 'Generative Engine Optimization (GEO) is the future of SEO. By structuring data properly, you help AI models extract and synthesize information.';
$post->status = 'published';
$post->author_id = 1;
$post->published_at = now();
$post->setTranslations('geo_data', [
    'en' => [
        'ai_summary' => 'This article explains how to implement GEO in Laravel applications using JSON-LD schema.',
        'key_takeaways' => [
            ['point' => 'JSON-LD schema is crucial for AI readability.'],
            ['point' => 'Use semantic FAQs for better extraction.']
        ],
        'entities' => ['Laravel', 'GEO', 'AI'],
        'faqs' => [
            [
                'question' => 'What is GEO?',
                'answer' => 'Generative Engine Optimization is optimizing content for AI models.'
            ]
        ]
    ]
]);
$post->save();
