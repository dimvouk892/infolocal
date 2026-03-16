<?php

namespace App\Support;

use App\Models\PageText;

class PageTextHelper
{
    public static function get(string $page, string $key, ?string $fallback = null): ?string
    {
        return PageText::getValue($page, $key, app()->getLocale(), $fallback);
    }
}

