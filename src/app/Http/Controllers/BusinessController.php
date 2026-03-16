<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Village;
use App\Support\MapPin;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessController extends Controller
{
    private const DEFAULT_CATEGORIES = [
        'hotels' => 'Hotels',
        'apartments-villas' => 'Apartments / Villas',
        'restaurants-taverns' => 'Restaurants / Taverns',
        'cafes-bars' => 'Cafes / Bars',
        'car-rentals' => 'Car Rentals',
        'boat-rentals' => 'Boat Rentals',
        'travel-agencies' => 'Travel Agencies',
        'local-shops' => 'Local Shops / Traditional Products',
        'activities-excursions' => 'Activities / Excursions',
        'wellness-spa' => 'Wellness / Spa',
    ];

    public function index(Request $request): View
    {
        try {
            $categories = BusinessCategory::orderBy('sort_order')->get()->mapWithKeys(fn ($c) => [$c->slug => $c->name]);
        } catch (QueryException) {
            $categories = collect(self::DEFAULT_CATEGORIES);
        }
        if ($categories->isEmpty()) {
            $categories = collect(self::DEFAULT_CATEGORIES);
        }

        $currentCategory = $request->query('category', '');
        $currentVillage = $request->query('village', '');

        try {
            $villages = \Illuminate\Support\Facades\Schema::hasTable('villages')
                ? Village::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'name_el', 'slug'])
                : collect();
        } catch (QueryException) {
            $villages = collect();
        }

        try {
            $businesses = Business::published()
                ->with(['category', 'villages'])
                ->when($currentCategory, fn ($q) => $q->whereHas('category', fn ($q2) => $q2->where('slug', $currentCategory)))
                ->when($currentVillage, fn ($q) => $q->whereHas('villages', fn ($q2) => $q2->where('villages.slug', $currentVillage)))
                ->orderBy('featured', 'desc')
                ->orderBy('name')
                ->get();
        } catch (QueryException) {
            $businesses = collect($this->defaultBusinesses());
        }

        if ($businesses->isEmpty()) {
            $businesses = collect($this->defaultBusinesses());
        }
        if ($currentCategory && $businesses->isNotEmpty() && ! $businesses->first() instanceof Business) {
            $businesses = $businesses->filter(fn ($b) => ($b['category_slug'] ?? '') === $currentCategory)->values();
        }

        return view('businesses', [
            'businesses' => $businesses,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'villages' => $villages,
            'currentVillage' => $currentVillage,
        ]);
    }

    public function show(string $slug): View
    {
        try {
            $business = Business::published()
                ->where('slug', $slug)
                ->with([
                    'category',
                    'reviews' => fn ($query) => $query->approved()->latest(),
                ])
                ->first();
        } catch (QueryException) {
            $business = null;
        }

        if (! $business) {
            $collection = collect($this->defaultBusinesses());
            $business = $collection->firstWhere('slug', $slug);
            if (! $business) {
                abort(404);
            }
        }

        return view('business-show', [
            'business' => $business,
        ]);
    }

    public function onMap(Request $request): View
    {
        $selectedVillage = $request->query('village', '');

        $businesses = Business::published()
            ->onMap()
            ->with(['category', 'villages'])
            ->orderBy('name')
            ->get()
            ->filter(function ($b) {
                $loc = $b->map_location;
                return is_array($loc) && isset($loc['lat'], $loc['lng']);
            })
            ->values();

        $places = Place::published()
            ->with(['category', 'villages'])
            ->ordered()
            ->get()
            ->filter(function ($place) {
                $loc = $place->map_location;
                return is_array($loc) && isset($loc['lat'], $loc['lng']);
            })
            ->values();

        $businessCategories = BusinessCategory::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'name_el', 'slug']);
        $placeCategories = PlaceCategory::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'slug']);
        $villages = \Illuminate\Support\Facades\Schema::hasTable('villages')
            ? Village::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'name_el', 'slug'])
            : collect();

        $businessPins = $businesses->map(fn ($b) => [
            'lat' => (float) $b->map_location['lat'],
            'lng' => (float) $b->map_location['lng'],
            'type' => 'business',
            'name' => $b->name,
            'slug' => $b->slug,
            'url' => route('businesses.show', $b->slug),
            'category' => $b->category?->name,
            'category_slug' => $b->category?->slug,
            'category_icon' => MapPin::normalizeIcon($b->category?->map_pin_icon),
            'category_color' => MapPin::normalizeColor($b->category?->map_pin_color),
            'address' => $b->address,
            'phone' => $b->phone,
            'village_slugs' => $b->villages->pluck('slug')->toArray(),
        ]);

        $placePins = $places->map(fn ($place) => [
            'lat' => (float) $place->map_location['lat'],
            'lng' => (float) $place->map_location['lng'],
            'type' => 'place',
            'name' => $place->title,
            'slug' => $place->slug,
            'url' => route('places.show', $place->slug),
            'category' => $place->category?->name,
            'category_slug' => $place->category?->slug,
            'category_icon' => MapPin::normalizeIcon($place->category?->map_pin_icon),
            'category_color' => MapPin::normalizeColor($place->category?->map_pin_color),
            'address' => $place->address,
            'phone' => $place->phone,
            'village_slugs' => $place->villages->pluck('slug')->toArray(),
        ]);

        $mapPins = $businessPins->concat($placePins)->values()->all();

        return view('businesses-on-map', [
            'businesses' => $businesses,
            'places' => $places,
            'mapPins' => $mapPins,
            'businessCategories' => $businessCategories,
            'placeCategories' => $placeCategories,
            'villages' => $villages,
            'selectedType' => $request->query('type', 'all'),
            'selectedBusinessCategory' => $request->query('business_category', ''),
            'selectedPlaceCategory' => $request->query('place_category', ''),
            'selectedVillage' => $selectedVillage,
        ]);
    }

    private function defaultBusinesses(): array
    {
        return [
            ['slug' => 'azure-breeze-hotel', 'title' => 'Azure Breeze Hotel', 'category' => 'Hotels', 'description' => 'Boutique seafront hotel with panoramic views.', 'address' => 'Seaside Avenue 12', 'phone' => '+30 210 000 0000', 'email' => 'info@example.com', 'website' => 'https://example.com', 'featured_image' => 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg', 'featured' => true, 'opening_hours' => 'Daily 07:00 – 23:00', 'social' => ['facebook' => '#', 'instagram' => '#'], 'category_slug' => 'hotels'],
            ['slug' => 'harbor-taste-tavern', 'title' => 'Harbor Taste Tavern', 'category' => 'Restaurants / Taverns', 'description' => 'Family-run tavern serving fresh seafood.', 'address' => 'Harbor Street 5', 'phone' => '+30 210 000 0001', 'email' => 'hello@example.gr', 'website' => 'https://example.gr', 'featured_image' => 'https://images.pexels.com/photos/260922/pexels-photo-260922.jpeg', 'featured' => true, 'opening_hours' => 'Daily 12:00 – 00:00', 'social' => ['facebook' => '#', 'instagram' => '#'], 'category_slug' => 'restaurants-taverns'],
        ];
    }
}
