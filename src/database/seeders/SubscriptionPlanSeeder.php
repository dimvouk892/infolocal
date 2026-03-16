<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::firstOrCreate(
            ['slug' => 'annual'],
            [
                'name' => 'Annual',
                'price' => 99.00,
                'duration' => '1 year',
                'duration_days' => 365,
                'is_active' => true,
                'description' => 'Annual listing in the business directory.',
            ]
        );
    }
}
