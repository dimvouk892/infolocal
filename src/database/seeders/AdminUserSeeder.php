<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Administrator: admin@visitmylopotamos.local / admin123
     * Business user: user@visitmylopotamos.local / user123
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@visitmylopotamos.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@visitmylopotamos.local'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('user123'),
                'role' => User::ROLE_USER,
                'is_active' => true,
            ]
        );
    }
}
