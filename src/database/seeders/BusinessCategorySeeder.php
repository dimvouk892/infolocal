<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'hotels' => ['en' => 'Hotels', 'el' => 'Ξενοδοχεία'],
            'apartments-villas' => ['en' => 'Apartments / Villas', 'el' => 'Διαμερίσματα / Βίλες'],
            'restaurants-taverns' => ['en' => 'Restaurants / Taverns', 'el' => 'Εστιατόρια / Ταβέρνες'],
            'cafes-bars' => ['en' => 'Cafes / Bars', 'el' => 'Καφέ / Μπαρ'],
            'car-rentals' => ['en' => 'Car Rentals', 'el' => 'Ενοικίαση αυτοκινήτων'],
            'boat-rentals' => ['en' => 'Boat Rentals', 'el' => 'Ενοικίαση σκαφών'],
            'travel-agencies' => ['en' => 'Travel Agencies', 'el' => 'Ταξιδιωτικά γραφεία'],
            'local-shops' => ['en' => 'Local Shops / Traditional Products', 'el' => 'Τοπικά καταστήματα / Παραδοσιακά προϊόντα'],
            'activities-excursions' => ['en' => 'Activities / Excursions', 'el' => 'Δραστηριότητες / Εκδρομές'],
            'wellness-spa' => ['en' => 'Wellness / Spa', 'el' => 'Wellness / Spa'],
        ];

        $sort = 0;
        foreach ($categories as $slug => $names) {
            BusinessCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $names['en'], 'name_el' => $names['el'], 'sort_order' => $sort++]
            );
        }
    }
}
