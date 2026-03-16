<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            PlaceCategorySeeder::class,
            BusinessCategorySeeder::class,
            SubscriptionPlanSeeder::class,
            DefaultContentSeeder::class,
        ]);
    }
}
