<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Database\Seeder;

class DefaultContentSeeder extends Seeder
{
    /**
     * Sync default content: places to visit and businesses for testing.
     */
    public function run(): void
    {
        $this->seedPlaces();
        $this->seedBusinesses();
    }

    private function seedPlaces(): void
    {
        $caves = PlaceCategory::where('slug', 'caves')->first();
        $beaches = PlaceCategory::where('slug', 'beaches')->first();
        $villages = PlaceCategory::where('slug', 'villages')->first();

        $places = [
            [
                'slug' => 'melidoni-cave',
                'title' => 'Σπήλαιο Μελιδονίου (Melidoni Cave)',
                'short_description' => 'Ιστορικό σπήλαιο με εντυπωσιακούς σταλακτίτες και σημαντική ιστορία. Ανοιχτό για επισκέπτες.',
                'full_content' => '<p>Το Σπήλαιο Μελιδονίου είναι ένα από τα σημαντικότερα αξιοθέατα της περιοχής Μυλοποτάμου. Προσφέρει ξενάγηση και πληροφορίες για τη γεωλογία και την ιστορία του.</p>',
                'place_category_id' => $caves?->id,
                'featured_image' => 'https://images.pexels.com/photos/7262772/pexels-photo-7262772.jpeg?auto=compress&cs=tinysrgb&w=800',
                'coordinates' => ['lat' => 35.4167, 'lng' => 24.7167],
                'address' => 'Melidoni, Rethymno',
                'status' => 'published',
                'sort_order' => 1,
                'featured' => true,
            ],
            [
                'slug' => 'panagia-beach',
                'title' => 'Παραλία Παναγίας',
                'short_description' => 'Όμορφη αμμώδης παραλία με καθαρά νερά, ιδανική για οικογένειες.',
                'full_content' => '<p>Η Παναγία είναι μια ήσυχη παραλία στη βόρεια ακτή της Κρήτης, κοντά στην περιοχή Μυλοποτάμου. Κατάλληλη για κολύμπι και ηλιοθεραπεία.</p>',
                'place_category_id' => $beaches?->id,
                'featured_image' => 'https://images.pexels.com/photos/1001682/pexels-photo-1001682.jpeg?auto=compress&cs=tinysrgb&w=800',
                'coordinates' => ['lat' => 35.4500, 'lng' => 24.6500],
                'address' => 'Panagia, Mylopotamos',
                'status' => 'published',
                'sort_order' => 2,
                'featured' => true,
            ],
            [
                'slug' => 'margarites-village',
                'title' => 'Μαργαρίτες – Παραδοσιακό χωριό αγγειοπλαστικής',
                'short_description' => 'Διασημό χωριό με κεραμική παράδοση, εργαστήρια και καφενεία.',
                'full_content' => '<p>Οι Μαργαρίτες είναι γνωστοί για την αγγειοπλαστική. Επισκέψου εργαστήρια, δες ζωντανή επίδειξη και αγόρασε κεραμικά.</p>',
                'place_category_id' => $villages?->id,
                'featured_image' => 'https://images.pexels.com/photos/672532/pexels-photo-672532.jpeg?auto=compress&cs=tinysrgb&w=800',
                'coordinates' => ['lat' => 35.3833, 'lng' => 24.6833],
                'address' => 'Margarites, Mylopotamos',
                'status' => 'published',
                'sort_order' => 3,
                'featured' => true,
            ],
        ];

        foreach ($places as $data) {
            Place::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }

    private function seedBusinesses(): void
    {
        $hotels = BusinessCategory::where('slug', 'hotels')->first();
        $restaurants = BusinessCategory::where('slug', 'restaurants-taverns')->first();
        $villas = BusinessCategory::where('slug', 'apartments-villas')->first();
        $cafes = BusinessCategory::where('slug', 'cafes-bars')->first();
        $carRentals = BusinessCategory::where('slug', 'car-rentals')->first();
        $shops = BusinessCategory::where('slug', 'local-shops')->first();

        $businesses = [
            [
                'slug' => 'azure-breeze-hotel',
                'name' => 'Azure Breeze Hotel',
                'description' => 'Boutique seafront hotel with panoramic views and curated local experiences.',
                'address' => 'Seaside Avenue 12, Sunset Bay',
                'phone' => '+30 210 000 0000',
                'email' => 'info@azurebreezehotel.com',
                'website' => 'https://azurebreezehotel.com',
                'featured_image' => 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg',
                'opening_hours' => ['description' => 'Daily 07:00 – 23:00'],
                'social_links' => ['facebook' => '#', 'instagram' => '#', 'tripadvisor' => '#'],
                'business_category_id' => $hotels?->id,
                'featured' => true,
            ],
            [
                'slug' => 'harbor-taste-tavern',
                'name' => 'Harbor Taste Tavern',
                'description' => 'Family-run tavern serving fresh seafood and traditional recipes.',
                'address' => 'Harbor Street 5, Old Port',
                'phone' => '+30 210 000 0001',
                'email' => 'hello@harbortaste.gr',
                'website' => 'https://harbortaste.gr',
                'featured_image' => 'https://images.pexels.com/photos/260922/pexels-photo-260922.jpeg',
                'opening_hours' => ['description' => 'Daily 12:00 – 00:00'],
                'social_links' => ['facebook' => '#', 'instagram' => '#'],
                'business_category_id' => $restaurants?->id,
                'featured' => true,
            ],
            [
                'slug' => 'olive-grove-retreat-villas',
                'name' => 'Olive Grove Retreat Villas',
                'description' => 'Elegant villas tucked among olive trees with private pools.',
                'address' => 'Olive Valley Road 21, Hillside',
                'phone' => '+30 210 000 0002',
                'email' => 'stay@olivegroveretreat.com',
                'website' => 'https://olivegroveretreat.com',
                'featured_image' => 'https://images.pexels.com/photos/2406773/pexels-photo-2406773.jpeg',
                'opening_hours' => ['description' => 'Check-in 15:00 – Check-out 11:00'],
                'social_links' => ['instagram' => '#'],
                'business_category_id' => $villas?->id,
                'featured' => false,
            ],
            [
                'slug' => 'mylopotamos-cafe',
                'name' => 'Mylopotamos Cafe',
                'description' => 'Καφές και ελαφρύ σνακ με θέα στην πλατεία. Ιδανικό για πρωινό ή απόγευμα.',
                'address' => 'Plateia 3, Mylopotamos',
                'phone' => '+30 28340 50010',
                'email' => 'info@mylopotamoscafe.gr',
                'website' => null,
                'featured_image' => 'https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?auto=compress&cs=tinysrgb&w=800',
                'opening_hours' => ['description' => 'Τρί – Κυρ 08:00 – 22:00'],
                'social_links' => ['facebook' => '#', 'instagram' => '#'],
                'business_category_id' => $cafes?->id,
                'featured' => false,
            ],
            [
                'slug' => 'crete-drive-rentals',
                'name' => 'Crete Drive Rentals',
                'description' => 'Ενοικίαση αυτοκινήτων και μηχανών για την εξερεύνηση της περιοχής.',
                'address' => 'Main Road, Perama',
                'phone' => '+30 28340 50120',
                'email' => 'book@cretedriverentals.gr',
                'website' => 'https://cretedriverentals.gr',
                'featured_image' => 'https://images.pexels.com/photos/3801780/pexels-photo-3801780.jpeg?auto=compress&cs=tinysrgb&w=800',
                'opening_hours' => ['description' => 'Καθημερινά 08:00 – 20:00'],
                'social_links' => ['facebook' => '#'],
                'business_category_id' => $carRentals?->id,
                'featured' => false,
            ],
            [
                'slug' => 'local-tastes-shop',
                'name' => 'Local Tastes – Παραδοσιακά προϊόντα',
                'description' => 'Λάδι, μέλι, τυριά και τοπικά προϊόντα από την περιοχή Μυλοποτάμου.',
                'address' => 'Margarites Village, Main Street 8',
                'phone' => '+30 28340 50230',
                'email' => 'hello@localtastes.gr',
                'website' => null,
                'featured_image' => 'https://images.pexels.com/photos/1407846/pexels-photo-1407846.jpeg?auto=compress&cs=tinysrgb&w=800',
                'opening_hours' => ['description' => 'Δευ – Σαβ 09:00 – 18:00'],
                'social_links' => ['instagram' => '#'],
                'business_category_id' => $shops?->id,
                'featured' => false,
            ],
        ];

        foreach ($businesses as $data) {
            Business::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['status' => 'published'])
            );
        }
    }
}
