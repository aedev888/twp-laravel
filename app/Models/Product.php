<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductPriceType;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price_type',
        'price',
        'demo_url',
        'version',
        'status',
        'published_at',
        'seo',
        'changelog',
        'faq',
        'views_count',
        'downloads_count',
        'average_rating',
        'likes_count',
        'dislikes_count',
        'preview_path',
        'file_path',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo' => 'array',
        'changelog' => 'array',
        'faq' => 'array',
        'status' => ProductStatus::class,
        'price_type' => ProductPriceType::class,
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'average_rating' => 'float',
    ];

    /**
     * Slug is NOT translatable to ensure stable URLs and route model binding.
     */
    public $translatable = [
        'title',
        'description',
        'changelog',
        'faq',
    ];

    public function taxonomies(): MorphToMany
    {
        return $this->morphToMany(Taxonomy::class, 'taxonomable');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('preview')
            ->singleFile();

        $this->addMediaCollection('file')
            ->singleFile();
            
        $this->addMediaCollection('gallery');
    }

    public function getPreviewUrl(): string
    {
        if ($this->preview_path) {
            return asset('storage/' . $this->preview_path);
        }
        
        return $this->getFirstMediaUrl('preview') ?: 'https://placehold.co/600x400/1e1e2d/white?text=' . urlencode($this->title);
    }

    /**
     * Returns schema data for SEO and API
     */
    public function getSchemaData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => $this->title,
            'operatingSystem' => 'WordPress',
            'applicationCategory' => $this->relationLoaded('taxonomies') ? ($this->taxonomies->first()?->name ?? 'Plugin') : 'Plugin',
            'offers' => [
                '@type' => 'Offer',
                'price' => $this->price ?? '0',
                'priceCurrency' => 'USD',
            ],
            'aggregateRating' => $this->average_rating > 0 ? [
                '@type' => 'AggregateRating',
                'ratingValue' => $this->average_rating,
                'reviewCount' => $this->reviews_count ?? $this->reviews()->count(),
            ] : null,
        ];
    }

    /**
     * Override toArray to handle translations for Filament/API
     */
    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            if (isset($attributes[$field]) && is_array($attributes[$field])) {
                $attributes[$field] = $this->getTranslation($field, app()->getLocale(), false) ?: null;
            }
        }
        return $attributes;
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class)->where('is_published', true);
    }

    public function allReviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute($value): float
    {
        return (float) ($value ?? 0.0);
    }

    public function votes(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function getVotePercentageAttribute(): int
    {
        $total = $this->likes_count + $this->dislikes_count;
        if ($total === 0) return 0;
        return (int) (($this->likes_count / $total) * 100);
    }
}
