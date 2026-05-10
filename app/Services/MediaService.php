<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Page;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    public function deleteOrphanedFiles(): int
    {
        // Example implementation to clean up media not attached to any model
        return 0; 
    }
    
    public function getMediaUrl(Media $media, string $conversion = ''): string
    {
        return $media->getUrl($conversion);
    }
}
