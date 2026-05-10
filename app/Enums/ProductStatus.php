<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductStatus: string implements HasLabel
{
    case Draft     = 'draft';
    case Published = 'published';
    case Archived  = 'archived';

    public function getLabel(): ?string
    {
        return match($this) {
            self::Draft     => 'Черновик',
            self::Published => 'Опубликован',
            self::Archived  => 'Архив',
        };
    }
}
