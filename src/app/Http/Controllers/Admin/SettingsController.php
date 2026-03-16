<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        try {
            $settings = Setting::getSettingsObject();
        } catch (\Throwable $e) {
            $settings = (object) [];
        }
        return view('admin.settings.index', ['settings' => $settings]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'og_site_name' => ['nullable', 'string', 'max:255'],
            'admin_primary_color' => ['nullable', 'string', 'max:20'],
            'admin_primary_hover_color' => ['nullable', 'string', 'max:20'],
            'admin_background_color' => ['nullable', 'string', 'max:20'],
            'admin_sidebar_background_color' => ['nullable', 'string', 'max:20'],
            'admin_text_primary_color' => ['nullable', 'string', 'max:20'],
            'admin_text_secondary_color' => ['nullable', 'string', 'max:20'],
            'admin_sidebar_active_color' => ['nullable', 'string', 'max:20'],
            'user_primary_color' => ['nullable', 'string', 'max:20'],
            'user_primary_hover_color' => ['nullable', 'string', 'max:20'],
            'user_secondary_color' => ['nullable', 'string', 'max:20'],
            'user_secondary_hover_color' => ['nullable', 'string', 'max:20'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'primary_hover_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'secondary_hover_color' => ['nullable', 'string', 'max:20'],
            'accent_color' => ['nullable', 'string', 'max:20'],
            'accent_hover_color' => ['nullable', 'string', 'max:20'],
            'button_background_color' => ['nullable', 'string', 'max:20'],
            'button_hover_color' => ['nullable', 'string', 'max:20'],
            'link_color' => ['nullable', 'string', 'max:20'],
            'link_hover_color' => ['nullable', 'string', 'max:20'],
            'header_background_color' => ['nullable', 'string', 'max:20'],
            'header_text_color' => ['nullable', 'string', 'max:20'],
            'nav_hover_color' => ['nullable', 'string', 'max:20'],
            'footer_background_color' => ['nullable', 'string', 'max:20'],
            'footer_text_color' => ['nullable', 'string', 'max:20'],
            'body_background_color' => ['nullable', 'string', 'max:20'],
            'section_background_color' => ['nullable', 'string', 'max:20'],
            'border_color' => ['nullable', 'string', 'max:20'],
            'success_color' => ['nullable', 'string', 'max:20'],
            'warning_color' => ['nullable', 'string', 'max:20'],
            'error_color' => ['nullable', 'string', 'max:20'],
            'info_color' => ['nullable', 'string', 'max:20'],
            'main_background_color' => ['nullable', 'string', 'max:20'],
            'soft_background_color' => ['nullable', 'string', 'max:20'],
            'text_primary_color' => ['nullable', 'string', 'max:20'],
            'text_secondary_color' => ['nullable', 'string', 'max:20'],
            'text_muted_color' => ['nullable', 'string', 'max:20'],
            'border_light_color' => ['nullable', 'string', 'max:20'],
            'border_strong_color' => ['nullable', 'string', 'max:20'],
            'neutral_color' => ['nullable', 'string', 'max:20'],
            'logo_frontend' => ['nullable', 'string', 'max:500'],
            'logo_frontend_height' => ['nullable', 'integer', 'min:24', 'max:220'],
            'logo_frontend_max_width' => ['nullable', 'integer', 'min:60', 'max:480'],
            'logo_frontend_alignment' => ['nullable', 'in:left,center'],
            'logo_admin' => ['nullable', 'string', 'max:500'],
            'logo_admin_height' => ['nullable', 'integer', 'min:24', 'max:160'],
            'logo_admin_max_width' => ['nullable', 'integer', 'min:60', 'max:320'],
            'logo_admin_alignment' => ['nullable', 'in:left,center'],
            'logo_footer' => ['nullable', 'string', 'max:500'],
            'logo_footer_height' => ['nullable', 'integer', 'min:20', 'max:120'],
            'logo_footer_max_width' => ['nullable', 'integer', 'min:60', 'max:280'],
            'favicon' => ['nullable', 'string', 'max:500'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'hero_highlight_1' => ['nullable', 'string', 'max:255'],
            'hero_highlight_2' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'hero_slideshow_interval' => ['nullable', 'integer', 'min:2', 'max:60'],
            'hero_slideshow_transition' => ['nullable', 'numeric', 'min:0.5', 'max:5'],
            'hero_overlay' => ['nullable', 'string', 'max:20'],
            'hero_overlay_color' => ['nullable', 'string', 'max:50'],
            'promoted_businesses_title' => ['nullable', 'string', 'max:255'],
            'promoted_businesses_subtitle' => ['nullable', 'string', 'max:500'],
            'why_title' => ['nullable', 'string', 'max:255'],
            'why_intro' => ['nullable', 'string', 'max:1000'],
            'why_point1_title' => ['nullable', 'string', 'max:255'],
            'why_point1_body' => ['nullable', 'string', 'max:500'],
            'why_point2_title' => ['nullable', 'string', 'max:255'],
            'why_point2_body' => ['nullable', 'string', 'max:500'],
            'why_point3_title' => ['nullable', 'string', 'max:255'],
            'why_point3_body' => ['nullable', 'string', 'max:500'],
            'testimonials_badge' => ['nullable', 'string', 'max:255'],
            'testimonials_quote' => ['nullable', 'string', 'max:500'],
            'testimonials_name' => ['nullable', 'string', 'max:255'],
            'testimonials_note' => ['nullable', 'string', 'max:500'],
            'cta_title' => ['nullable', 'string', 'max:255'],
            'cta_subtitle' => ['nullable', 'string', 'max:500'],
            'cta_primary' => ['nullable', 'string', 'max:255'],
            'cta_secondary' => ['nullable', 'string', 'max:255'],
            'contact_teaser' => ['nullable', 'string', 'max:500'],
            'footer_content' => ['nullable', 'string', 'max:3000'],
            'footer_explore' => ['nullable', 'string', 'max:100'],
            'footer_connect' => ['nullable', 'string', 'max:100'],
            'footer_rights' => ['nullable', 'string', 'max:255'],
            'nav_home' => ['nullable', 'string', 'max:80'],
            'nav_places_to_visit' => ['nullable', 'string', 'max:80'],
            'nav_businesses' => ['nullable', 'string', 'max:80'],
            'nav_on_map' => ['nullable', 'string', 'max:80'],
            'nav_about' => ['nullable', 'string', 'max:80'],
            'nav_contact' => ['nullable', 'string', 'max:80'],
            'on_map_title' => ['nullable', 'string', 'max:255'],
            'on_map_subtitle' => ['nullable', 'string', 'max:500'],
            'businesses_title' => ['nullable', 'string', 'max:255'],
            'businesses_subtitle' => ['nullable', 'string', 'max:500'],
            'places_title' => ['nullable', 'string', 'max:255'],
            'places_subtitle' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'privacy_policy' => ['nullable', 'string', 'max:50000'],
            'privacy_policy_el' => ['nullable', 'string', 'max:50000'],
            'terms_of_use' => ['nullable', 'string', 'max:50000'],
            'terms_of_use_el' => ['nullable', 'string', 'max:50000'],
            'site_under_construction' => ['nullable', 'in:0,1'],
            'featured_places_title' => ['nullable', 'string', 'max:255'],
            'featured_places_subtitle' => ['nullable', 'string', 'max:500'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_intro' => ['nullable', 'string', 'max:2000'],
            'about_section1_title' => ['nullable', 'string', 'max:255'],
            'about_section1_body' => ['nullable', 'string', 'max:2000'],
            'about_section2_title' => ['nullable', 'string', 'max:255'],
            'about_section2_body' => ['nullable', 'string', 'max:2000'],
            'about_section3_title' => ['nullable', 'string', 'max:255'],
            'about_section3_body' => ['nullable', 'string', 'max:2000'],
            'places_intro' => ['nullable', 'string', 'max:3000'],
            'activities_title' => ['nullable', 'string', 'max:255'],
            'activities_intro' => ['nullable', 'string', 'max:3000'],
            'beaches_title' => ['nullable', 'string', 'max:255'],
            'beaches_intro' => ['nullable', 'string', 'max:3000'],
            'businesses_intro' => ['nullable', 'string', 'max:3000'],
            'accommodation_title' => ['nullable', 'string', 'max:255'],
            'accommodation_intro' => ['nullable', 'string', 'max:3000'],
            'food_title' => ['nullable', 'string', 'max:255'],
            'food_intro' => ['nullable', 'string', 'max:3000'],
            'contact_title' => ['nullable', 'string', 'max:255'],
            'contact_subtitle' => ['nullable', 'string', 'max:500'],
            'contact_intro' => ['nullable', 'string', 'max:2000'],
        ]);

        $keys = [
            'site_title', 'tagline', 'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'og_site_name',
            'admin_primary_color', 'admin_primary_hover_color', 'admin_background_color',
            'admin_sidebar_background_color',
            'admin_text_primary_color', 'admin_text_secondary_color', 'admin_sidebar_active_color',
            'user_primary_color', 'user_primary_hover_color', 'user_secondary_color', 'user_secondary_hover_color',
            'primary_color', 'primary_hover_color', 'secondary_color', 'secondary_hover_color',
            'accent_color', 'accent_hover_color',
            'button_background_color', 'button_hover_color', 'link_color', 'link_hover_color',
            'header_background_color', 'header_text_color', 'nav_hover_color', 'footer_background_color', 'footer_text_color',
            'body_background_color', 'section_background_color', 'border_color',
            'success_color', 'warning_color', 'error_color', 'info_color',
            'main_background_color', 'soft_background_color', 'text_primary_color',
            'text_secondary_color', 'text_muted_color', 'border_light_color', 'border_strong_color',
            'neutral_color',
            'logo_frontend', 'logo_frontend_height', 'logo_frontend_max_width', 'logo_frontend_alignment',
            'logo_admin', 'logo_admin_height', 'logo_admin_max_width', 'logo_admin_alignment',
            'logo_footer', 'logo_footer_height', 'logo_footer_max_width',
            'favicon',
            'hero_title', 'hero_subtitle', 'hero_badge', 'hero_highlight_1', 'hero_highlight_2',
            'hero_image', 'hero_slideshow_interval', 'hero_slideshow_transition', 'hero_overlay', 'hero_overlay_color',
            'promoted_businesses_title', 'promoted_businesses_subtitle',
            'why_title', 'why_intro',
            'why_point1_title', 'why_point1_body', 'why_point2_title', 'why_point2_body',
            'why_point3_title', 'why_point3_body',
            'testimonials_badge', 'testimonials_quote', 'testimonials_name', 'testimonials_note',
            'cta_title', 'cta_subtitle', 'cta_primary', 'cta_secondary',
            'contact_teaser',
            'footer_content', 'footer_explore', 'footer_connect', 'footer_rights',
            'nav_home', 'nav_places_to_visit', 'nav_businesses', 'nav_on_map', 'nav_about', 'nav_contact',
            'on_map_title', 'on_map_subtitle',
            'businesses_title', 'businesses_subtitle', 'places_title', 'places_subtitle',
            'contact_email', 'contact_phone',
            'privacy_policy', 'privacy_policy_el', 'terms_of_use', 'terms_of_use_el',
            'site_under_construction',
            'featured_places_title', 'featured_places_subtitle',
            'about_title', 'about_intro',
            'about_section1_title', 'about_section1_body', 'about_section2_title', 'about_section2_body',
            'about_section3_title', 'about_section3_body',
            'places_intro', 'activities_title', 'activities_intro', 'beaches_title', 'beaches_intro',
            'businesses_intro', 'accommodation_title', 'accommodation_intro', 'food_title', 'food_intro',
            'contact_title', 'contact_subtitle', 'contact_intro',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        if ($request->has('social_links_json')) {
            $raw = $request->input('social_links_json');
            $decoded = is_string($raw) && trim($raw) !== '' ? json_decode($raw, true) : [];
            Setting::set('social_links', is_array($decoded) ? $decoded : []);
        }

        if ($request->has('hero_images_text')) {
            $lines = array_values(array_filter(array_map('trim', explode("\n", (string) $request->input('hero_images_text', '')))));
            Setting::set('hero_images', $lines);
        }

        return redirect()
            ->route('admin.settings.index', [
                'tab' => $request->input('current_tab', 'general'),
                'color_group' => $request->input('current_color_group', 'admin'),
            ])
            ->with('success', __('Settings saved.'));
    }
}
