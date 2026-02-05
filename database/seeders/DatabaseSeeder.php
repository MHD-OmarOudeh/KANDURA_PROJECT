<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Permissions & Roles
            RolePermissionSeeder::class,
            OrderPermissionSeeder::class,
            WalletPermissionSeeder::class,
            CouponPermissionSeeder::class,

            // Users
            UserSeeder::class,

            // Reference data
            CitiesSeeder::class,
            MeasurementSeeder::class,
            DesignOptionSeeder::class,

            // أي Seeders إضافية عندك ضيفها هون
        ]);
    }
}
