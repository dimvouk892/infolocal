<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Place;
use Illuminate\Database\QueryException;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredBusinesses = $this->getFeaturedBusinesses();
        $featuredPlaces = $this->getFeaturedPlaces();
        $featuredTours = []; // optional: Tour model later

        return view('home', [
            'featuredTours' => $featuredTours,
            'featuredBusinesses' => $featuredBusinesses,
            'featuredPlaces' => $featuredPlaces,
        ]);
    }

    private function getFeaturedPlaces()
    {
        try {
            $featured = Place::published()->where('featured', true)->with('category')->ordered()->limit(6)->get();
            return $featured->isEmpty()
                ? Place::published()->with('category')->ordered()->limit(6)->get()
                : $featured;
        } catch (QueryException) {
            return collect();
        }
    }

    private function getFeaturedBusinesses()
    {
        try {
            $items = Business::published()->where('featured', true)->limit(6)->get();
            return $items->isEmpty() ? collect($this->defaultBusinesses())->filter(fn ($b) => ! empty($b['featured'])) : $items;
        } catch (QueryException) {
            return collect($this->defaultBusinesses())->filter(fn ($b) => ! empty($b['featured']));
        }
    }

    private function defaultBusinesses(): array
    {
        return [
            ['slug' => 'azure-breeze-hotel', 'title' => 'Azure Breeze Hotel', 'category' => 'Hotels', 'description' => 'Boutique seafront hotel.', 'address' => 'Seaside Avenue 12', 'phone' => '+30 210 000 0000', 'featured_image' => 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg', 'featured' => true, 'opening_hours' => 'Daily 07:00 – 23:00'],
            ['slug' => 'harbor-taste-tavern', 'title' => 'Harbor Taste Tavern', 'category' => 'Restaurants', 'description' => 'Family-run tavern.', 'address' => 'Harbor Street 5', 'phone' => '+30 210 000 0001', 'featured_image' => 'https://images.pexels.com/photos/260922/pexels-photo-260922.jpeg', 'featured' => true, 'opening_hours' => 'Daily 12:00 – 00:00'],
        ];
    }
}
