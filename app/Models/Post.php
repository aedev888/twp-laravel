<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'published_at',
        'author_id',
        'featured_image',
        'seo',
        'geo_data',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo' => 'array',
        'geo_data' => 'array',
        'author_id' => 'integer',
    ];

    /**
     * Translatable attributes.
     */
    public $translatable = [
        'title',
        'excerpt',
        'content',
        'geo_data',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'post_taxonomy');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(300)
            ->height(200)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(800)
            ->format('webp')
            ->nonQueued();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * Safe translation retrieval with fallback.
     * Ensures that 'title', 'excerpt', and 'content' are ALWAYS strings.
     * Ensures that 'geo_data' is ALWAYS an array.
     */
    public function getTranslated(string $key, string $locale = null): mixed
    {
        $locale = $locale ?: app()->getLocale();
        
        // Use Spatie's native getTranslation first
        $value = $this->getTranslation($key, $locale, false);
        
        // Fallback logic
        if (empty($value)) {
            $value = $this->getTranslation($key, 'en', false) 
                ?: $this->getTranslation($key, 'ru', false)
                ?: (array_values($this->getTranslations($key))[0] ?? null);
        }

        return $value ?? '';
    }

    /**
     * Overriding getAttribute to use our safe translation method.
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->getTranslatableAttributes())) {
            return $this->getTranslated($key);
        }

        return parent::getAttribute($key);
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getAttribute($field);
        }
        return $attributes;
    }

    public function getFeaturedImageUrl(): string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }

        return 'https://via.placeholder.com/1200x800?text=Lumina+Insights';
    }

    public function getUrl(): string
    {
        return route('blog.show', $this->slug);
    }
}
