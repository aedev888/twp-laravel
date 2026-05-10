<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTranslations('title'),
            'slug' => $this->slug,
            'description' => $this->getTranslations('description'),
            'version' => $this->version,
            'price_type' => $this->price_type,
            'demo_url' => $this->demo_url,
            'status' => $this->status,
            'preview' => $this->getMedia('preview')->map(fn ($media) => [
                'url' => $media->getUrl(),
                'thumbnail' => $media->getUrl('thumbnail'),
            ]),
            'schema' => $this->getSchemaData(),
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
        ];
    }
}
