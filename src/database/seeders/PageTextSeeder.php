<?php

namespace Database\Seeders;

use App\Models\PageText;
use Illuminate\Database\Seeder;

class PageTextSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            // HOME
            ['page' => 'home', 'key' => 'hero_title', 'locale' => 'en', 'value' => __('messages.home.hero_title', [], 'en')],
            ['page' => 'home', 'key' => 'hero_subtitle', 'locale' => 'en', 'value' => __('messages.home.hero_subtitle', [], 'en')],
            ['page' => 'home', 'key' => 'promoted_businesses_title', 'locale' => 'en', 'value' => __('messages.home.promoted_businesses_title', [], 'en')],
            ['page' => 'home', 'key' => 'promoted_businesses_subtitle', 'locale' => 'en', 'value' => __('messages.home.promoted_businesses_subtitle', [], 'en')],
            ['page' => 'home', 'key' => 'why_title', 'locale' => 'en', 'value' => __('messages.home.why_title', [], 'en')],
            ['page' => 'home', 'key' => 'why_intro', 'locale' => 'en', 'value' => __('messages.home.why_intro', [], 'en')],

            ['page' => 'home', 'key' => 'hero_title', 'locale' => 'el', 'value' => __('messages.home.hero_title', [], 'el')],
            ['page' => 'home', 'key' => 'hero_subtitle', 'locale' => 'el', 'value' => __('messages.home.hero_subtitle', [], 'el')],
            ['page' => 'home', 'key' => 'promoted_businesses_title', 'locale' => 'el', 'value' => __('messages.home.promoted_businesses_title', [], 'el')],
            ['page' => 'home', 'key' => 'promoted_businesses_subtitle', 'locale' => 'el', 'value' => __('messages.home.promoted_businesses_subtitle', [], 'el')],
            ['page' => 'home', 'key' => 'why_title', 'locale' => 'el', 'value' => __('messages.home.why_title', [], 'el')],
            ['page' => 'home', 'key' => 'why_intro', 'locale' => 'el', 'value' => __('messages.home.why_intro', [], 'el')],

            // ABOUT
            ['page' => 'about', 'key' => 'title', 'locale' => 'en', 'value' => __('messages.cms.about_title', [], 'en')],
            ['page' => 'about', 'key' => 'intro', 'locale' => 'en', 'value' => __('messages.cms.about_intro', [], 'en')],
            ['page' => 'about', 'key' => 'title', 'locale' => 'el', 'value' => __('messages.cms.about_title', [], 'el')],
            ['page' => 'about', 'key' => 'intro', 'locale' => 'el', 'value' => __('messages.cms.about_intro', [], 'el')],

            // PLACES
            ['page' => 'places', 'key' => 'title', 'locale' => 'en', 'value' => __('messages.places.title', [], 'en')],
            ['page' => 'places', 'key' => 'intro', 'locale' => 'en', 'value' => __('messages.cms.places_intro', [], 'en')],
            ['page' => 'places', 'key' => 'title', 'locale' => 'el', 'value' => __('messages.places.title', [], 'el')],
            ['page' => 'places', 'key' => 'intro', 'locale' => 'el', 'value' => __('messages.cms.places_intro', [], 'el')],

            // BUSINESSES
            ['page' => 'businesses', 'key' => 'title', 'locale' => 'en', 'value' => __('messages.businesses.title', [], 'en')],
            ['page' => 'businesses', 'key' => 'subtitle', 'locale' => 'en', 'value' => __('messages.businesses.subtitle', [], 'en')],
            ['page' => 'businesses', 'key' => 'title', 'locale' => 'el', 'value' => __('messages.businesses.title', [], 'el')],
            ['page' => 'businesses', 'key' => 'subtitle', 'locale' => 'el', 'value' => __('messages.businesses.subtitle', [], 'el')],

            // MAP
            ['page' => 'map', 'key' => 'title', 'locale' => 'en', 'value' => __('messages.on_map.title', [], 'en')],
            ['page' => 'map', 'key' => 'subtitle', 'locale' => 'en', 'value' => __('messages.on_map.subtitle', [], 'en')],
            ['page' => 'map', 'key' => 'title', 'locale' => 'el', 'value' => __('messages.on_map.title', [], 'el')],
            ['page' => 'map', 'key' => 'subtitle', 'locale' => 'el', 'value' => __('messages.on_map.subtitle', [], 'el')],

            // CONTACT
            ['page' => 'contact', 'key' => 'title', 'locale' => 'en', 'value' => __('messages.cms.contact_title', [], 'en')],
            ['page' => 'contact', 'key' => 'subtitle', 'locale' => 'en', 'value' => __('messages.cms.contact_subtitle', [], 'en')],
            ['page' => 'contact', 'key' => 'intro', 'locale' => 'en', 'value' => __('messages.cms.contact_intro', [], 'en')],
            ['page' => 'contact', 'key' => 'title', 'locale' => 'el', 'value' => __('messages.cms.contact_title', [], 'el')],
            ['page' => 'contact', 'key' => 'subtitle', 'locale' => 'el', 'value' => __('messages.cms.contact_subtitle', [], 'el')],
            ['page' => 'contact', 'key' => 'intro', 'locale' => 'el', 'value' => __('messages.cms.contact_intro', [], 'el')],
        ];

        foreach ($entries as $entry) {
            PageText::updateOrCreate(
                [
                    'page'   => $entry['page'],
                    'key'    => $entry['key'],
                    'locale' => $entry['locale'],
                ],
                ['value' => $entry['value']],
            );
        }
    }
}

