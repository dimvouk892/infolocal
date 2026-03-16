<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageText extends Model
{
    protected $fillable = [
        'page',
        'key',
        'locale',
        'value',
    ];

    public static function getValue(string $page, string $key, ?string $locale = null, ?string $fallback = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        $record = static::where('page', $page)
            ->where('key', $key)
            ->where('locale', $locale)
            ->first();

        return $record?->value ?? $fallback;
    }
}

