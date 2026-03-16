<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::allAsKeyValue();
        return $settings[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) || is_object($value) ? json_encode($value) : $value]
        );
        self::clearCache();
    }

    public static function allAsKeyValue(): array
    {
        return Cache::rememberForever('app.settings', function () {
            $rows = self::all();
            $result = [];
            foreach ($rows as $row) {
                $val = $row->value;
                if (in_array($row->key, ['social_links', 'hero_images']) || (is_string($val) && (str_starts_with($val, '[') || str_starts_with($val, '{')))) {
                    $val = json_decode($val, true) ?? $val;
                }
                $result[$row->key] = $val;
            }
            return $result;
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('app.settings');
    }

    public static function getSettingsObject(): object
    {
        $data = self::allAsKeyValue();
        return (object) array_merge([
            'site_title' => null,
            'tagline' => null,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'og_image' => null,
            'og_site_name' => null,
            'site_under_construction' => 0,
            'admin_primary_color' => '#1E3A5F',
            'admin_primary_hover_color' => '#2A4A75',
            'admin_background_color' => '#F8FAFC',
            'admin_sidebar_background_color' => '#1E3A5F',
            'admin_text_primary_color' => '#0F172A',
            'admin_text_secondary_color' => '#64748B',
            'admin_sidebar_active_color' => '#10B981',
            'user_primary_color' => '#1E3A5F',
            'user_primary_hover_color' => '#2A4A75',
            'user_secondary_color' => '#E0F2FE',
            'user_secondary_hover_color' => '#BAE6FD',
            'button_background_color' => '#10B981',
            'button_hover_color' => '#059669',
            'link_color' => '#10B981',
            'link_hover_color' => '#059669',
            'header_background_color' => '#1E3A5F',
            'header_text_color' => '#FFFFFF',
            'nav_hover_color' => '#10B981',
            'footer_background_color' => '#1E3A5F',
            'footer_text_color' => '#FFFFFF',
            'body_background_color' => '#F8FAFC',
            'section_background_color' => '#FFFFFF',
            'border_color' => '#CBD5E1',
            'success_color' => '#22C55E',
            'warning_color' => '#F59E0B',
            'error_color' => '#EF4444',
            'info_color' => '#3B82F6',
            'main_background_color' => '#F8FAFC',
            'soft_background_color' => '#F1F5F9',
            'text_primary_color' => '#0F172A',
            'text_secondary_color' => '#334155',
            'text_muted_color' => '#64748B',
            'border_light_color' => '#E2E8F0',
            'border_strong_color' => '#94A3B8',
            'primary_color' => '#1E3A5F',
            'primary_hover_color' => '#2A4A75',
            'secondary_color' => '#E0F2FE',
            'secondary_hover_color' => '#BAE6FD',
            'accent_color' => '#10B981',
            'accent_hover_color' => '#059669',
            'neutral_color' => '#F8FAFC',
            'logo_frontend' => null,
            'logo_frontend_height' => 80,
            'logo_frontend_max_width' => 260,
            'logo_frontend_alignment' => 'left',
            'logo_admin' => null,
            'logo_admin_height' => 40,
            'logo_admin_max_width' => 140,
            'logo_admin_alignment' => 'center',
            'logo_footer' => null,
            'logo_footer_height' => 40,
            'logo_footer_max_width' => 160,
            'favicon' => null,
            'hero_title' => null,
            'hero_subtitle' => null,
            'hero_badge' => null,
            'hero_highlight_1' => null,
            'hero_highlight_2' => null,
            'hero_image' => null,
            'hero_images' => [],
            'hero_slideshow_interval' => 5,
            'hero_slideshow_transition' => 1.5,
            'hero_overlay' => null,
            'hero_overlay_color' => null,
            'promoted_businesses_title' => null,
            'promoted_businesses_subtitle' => null,
            'why_title' => null,
            'why_intro' => null,
            'why_point1_title' => null,
            'why_point1_body' => null,
            'why_point2_title' => null,
            'why_point2_body' => null,
            'why_point3_title' => null,
            'why_point3_body' => null,
            'testimonials_badge' => null,
            'testimonials_quote' => null,
            'testimonials_name' => null,
            'testimonials_note' => null,
            'cta_title' => null,
            'cta_subtitle' => null,
            'cta_primary' => null,
            'cta_secondary' => null,
            'contact_teaser' => null,
            'footer_content' => null,
            'footer_explore' => null,
            'footer_connect' => null,
            'footer_rights' => null,
            'nav_home' => null,
            'nav_places_to_visit' => null,
            'nav_businesses' => null,
            'nav_on_map' => null,
            'nav_about' => null,
            'nav_contact' => null,
            'on_map_title' => null,
            'on_map_subtitle' => null,
            'businesses_title' => null,
            'businesses_subtitle' => null,
            'places_title' => null,
            'places_subtitle' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'privacy_policy' => null,
            'terms_of_use' => null,
            'featured_places_title' => null,
            'featured_places_subtitle' => null,
            'about_title' => null,
            'about_intro' => null,
            'about_section1_title' => null,
            'about_section1_body' => null,
            'about_section2_title' => null,
            'about_section2_body' => null,
            'about_section3_title' => null,
            'about_section3_body' => null,
            'places_intro' => null,
            'activities_title' => null,
            'activities_intro' => null,
            'beaches_title' => null,
            'beaches_intro' => null,
            'businesses_intro' => null,
            'accommodation_title' => null,
            'accommodation_intro' => null,
            'food_title' => null,
            'food_intro' => null,
            'contact_title' => null,
            'contact_subtitle' => null,
            'contact_intro' => null,
            'social_links' => [],
        ], $data);
    }
}
