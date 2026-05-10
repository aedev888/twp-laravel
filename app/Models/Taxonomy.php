<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Taxonomy extends Model
{
    use HasTranslations;
    use LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    public $translatable = [
        'name',
        'slug',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function pages(): MorphToMany
    {
        return $this->morphedByMany(Page::class, 'taxonomable');
    }

    /**
     * Override getAttribute to provide locale fallbacks for translatable fields.
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->translatable)) {
            $value = $this->getTranslation($key, app()->getLocale(), false);
            
            if (empty($value)) {
                $value = $this->getTranslation($key, 'en', false) 
                    ?: $this->getTranslation($key, 'ru', false)
                    ?: (array_values($this->getTranslations($key))[0] ?? '');
            }
            
            return $value;
        }

        return parent::getAttribute($key);
    }

    public function getRouteKey()
    {
        return $this->slug;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();

        if ($field === 'slug') {
            return $this->where("slug->" . app()->getLocale(), $value)->first() 
                ?? $this->where("slug->en", $value)->first()
                ?? $this->where("slug->ru", $value)->first()
                ?? $this->where('slug', $value)->first()
                ?? abort(404);
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->translatable as $field) {
            $attributes[$field] = $this->getAttribute($field);
        }
        return $attributes;
    }
}
