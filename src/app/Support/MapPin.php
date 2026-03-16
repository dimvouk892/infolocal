<?php

namespace App\Support;

class MapPin
{
    public static function options(): array
    {
        return [
            'map-pin' => 'Default Pin',
            'store' => 'Store',
            'bed' => 'Hotel / Stay',
            'utensils' => 'Restaurant',
            'coffee' => 'Cafe',
            'bag' => 'Shopping',
            'camera' => 'Sightseeing',
            'tree' => 'Nature',
            'waves' => 'Beach / Sea',
            'landmark' => 'Landmark',
            'car' => 'Transport',
            'star' => 'Featured',
        ];
    }

    public static function paths(): array
    {
        return [
            'map-pin' => 'M24 13.5a4.5 4.5 0 1 0 0 9a4.5 4.5 0 0 0 0-9Z',
            'store' => 'M17 16.5V28m14-11.5V28M14 18.5h20M16 12h16l2 5.5H14L16 12Zm4 16V22h8v6',
            'bed' => 'M14 21h20m-20 0v7m20-7a3 3 0 0 1 3 3v4m-3-7V18a3 3 0 0 0-3-3H17a3 3 0 0 0-3 3v3m0 0v7m7-8h5',
            'utensils' => 'M18 12v16m-3-16v8a3 3 0 0 0 3 3m0-11v11m8-11v9m0 0c0 2.5 1.5 4.5 4 5v2',
            'coffee' => 'M16 18h12a0 0 0 0 1 0 0v4a6 6 0 0 1-6 6h0a6 6 0 0 1-6-6v-4a0 0 0 0 1 0 0Zm12 1h2a3 3 0 1 1 0 6h-2M18 12h8m-10 3h12',
            'bag' => 'M17 17h14l-1 12H18l-1-12Zm4-1v-2a3 3 0 1 1 6 0v2',
            'camera' => 'M16 18h16v10H16V18Zm4-3h8l1 2H19l1-2Zm4 10a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z',
            'tree' => 'M24 12l7 9h-4l5 6h-5v3h-6v-3h-5l5-6h-4l7-9Z',
            'waves' => 'M14 20c2 0 2-1.5 4-1.5S20 20 22 20s2-1.5 4-1.5S28 20 30 20s2-1.5 4-1.5M14 25c2 0 2-1.5 4-1.5S20 25 22 25s2-1.5 4-1.5S28 25 30 25s2-1.5 4-1.5',
            'landmark' => 'M16 18h16M18 18v10m4-10v10m4-10v10m4-10v10M15 28h18M24 12l10 4H14l10-4Z',
            'car' => 'M18 24h12l-1.5-5h-9L18 24Zm0 0-1 3m13-3 1 3M19 27a1.5 1.5 0 1 0 0 .01M29 27a1.5 1.5 0 1 0 0 .01',
            'star' => 'm24 13 2.8 5.8 6.4.9-4.6 4.4 1.1 6.3-5.7-3.1-5.7 3.1 1.1-6.3-4.6-4.4 6.4-.9L24 13Z',
        ];
    }

    public static function normalizeIcon(?string $icon): string
    {
        $icon = is_string($icon) ? $icon : 'map-pin';

        return array_key_exists($icon, self::paths()) ? $icon : 'map-pin';
    }

    public static function normalizeColor(?string $color): string
    {
        return is_string($color) && preg_match('/^#[0-9A-Fa-f]{6}$/', $color)
            ? strtoupper($color)
            : '#10B981';
    }

    public static function svg(string $icon, ?string $color = null): string
    {
        $resolvedIcon = self::normalizeIcon($icon);
        $resolvedColor = self::normalizeColor($color);
        $glyph = self::paths()[$resolvedIcon];

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 56" fill="none" aria-hidden="true">
  <path d="M24 3C14.611 3 7 10.611 7 20c0 11.83 13.496 24.84 16.018 27.153a1.5 1.5 0 0 0 1.964 0C27.504 44.84 41 31.83 41 20 41 10.611 33.389 3 24 3Z" fill="{$resolvedColor}" stroke="white" stroke-width="3"/>
  <circle cx="24" cy="20" r="11" fill="white" fill-opacity=".16"/>
  <path d="{$glyph}" fill="white" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
</svg>
SVG;
    }
}
