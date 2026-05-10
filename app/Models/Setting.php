<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public $translatable = [
        'value',
    ];
}
