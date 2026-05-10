<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'seo' => [
                'title' => $this->seo_title ?? $this->title,
                'description' => $this->seo_description,
            ],
            'media' => [
                'featured_image' => $this->getFirstMediaUrl('featured_image'),
                'thumbnail' => $this->getFirstMediaUrl('featured_image', 'thumbnail'),
                'medium' => $this->getFirstMediaUrl('featured_image', 'medium'),
            ],
            'taxonomies' => TaxonomyResource::collection($this->whenLoaded('taxonomies')),
            'published_at' => $this->published_at,
        ];
    }
}
