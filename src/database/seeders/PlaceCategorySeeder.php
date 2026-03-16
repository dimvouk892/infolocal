<?php

namespace Database\Seeders;

use App\Models\PlaceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlaceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Museums' => 'museums',
            'Beaches' => 'beaches',
            'Landmarks' => 'landmarks',
            'Parks' => 'parks',
            'Monuments' => 'monuments',
            'Historical locations' => 'historical-locations',
            'Attractions' => 'attractions',
            'Caves' => 'caves',
            'Villages' => 'villages',
            'Coast' => 'coast',
            'Archaeological sites' => 'archaeological-sites',
            'Towns' => 'towns',
        ];

        foreach ($categories as $name => $slug) {
            PlaceCategory::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'sort_order' => 0]
            );
        }
    }
}
