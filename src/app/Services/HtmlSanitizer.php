<?php

namespace App\Services;

class HtmlSanitizer
{
    /** Allowed HTML tags for rich text (description, content). */
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><a><ul><ol><li><span>';

    public static function sanitize(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return null;
        }

        $html = strip_tags($html, self::ALLOWED_TAGS);

        // Remove javascript: and data: URLs from href
        $html = preg_replace_callback(
            '/<a\s+([^>]*?)href\s*=\s*["\']([^"\']+)["\']([^>]*)>/i',
            function ($m) {
                $url = $m[2];
                if (preg_match('#^(javascript|data):#i', $url)) {
                    return '<a href="#" ' . $m[1] . $m[3] . '>';
                }
                return $m[0];
            },
            $html
        );

        return trim($html) !== '' ? $html : null;
    }
}
