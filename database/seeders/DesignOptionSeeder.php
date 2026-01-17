<?php

namespace Database\Seeders;

use App\Models\DesignOption;
use Illuminate\Database\Seeder;

class DesignOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Colors
            ['name' => ['en' => 'White', 'ar' => 'أبيض'], 'type' => 'color', 'color_code' => '#FFFFFF'],
            ['name' => ['en' => 'Black', 'ar' => 'أسود'], 'type' => 'color', 'color_code' => '#000000'],
            ['name' => ['en' => 'Beige', 'ar' => 'بيج'], 'type' => 'color', 'color_code' => '#F5F5DC'],
            ['name' => ['en' => 'Gray', 'ar' => 'رمادي'], 'type' => 'color', 'color_code' => '#808080'],
            ['name' => ['en' => 'Navy Blue', 'ar' => 'أزرق داكن'], 'type' => 'color', 'color_code' => '#000080'],
            ['name' => ['en' => 'Brown', 'ar' => 'بني'], 'type' => 'color', 'color_code' => '#8B4513'],

            // ✅ FIXED: dome_type not dome
            ['name' => ['en' => 'Traditional Dome', 'ar' => 'قبة تقليدية'], 'type' => 'dome_type'],
            ['name' => ['en' => 'Modern Dome', 'ar' => 'قبة عصرية'], 'type' => 'dome_type'],
            ['name' => ['en' => 'Flat Top', 'ar' => 'قمة مسطحة'], 'type' => 'dome_type'],
            ['name' => ['en' => 'Round Dome', 'ar' => 'قبة دائرية'], 'type' => 'dome_type'],

            // ✅ FIXED: fabric_type not fabric
            ['name' => ['en' => 'Cotton', 'ar' => 'قطن'], 'type' => 'fabric_type'],
            ['name' => ['en' => 'Linen', 'ar' => 'كتان'], 'type' => 'fabric_type'],
            ['name' => ['en' => 'Silk', 'ar' => 'حرير'], 'type' => 'fabric_type'],
            ['name' => ['en' => 'Premium Cotton', 'ar' => 'قطن فاخر'], 'type' => 'fabric_type'],
            ['name' => ['en' => 'Polyester', 'ar' => 'بوليستر'], 'type' => 'fabric_type'],

            // ✅ FIXED: sleeve_type not sleeve
            ['name' => ['en' => 'Long Sleeve', 'ar' => 'كم طويل'], 'type' => 'sleeve_type'],
            ['name' => ['en' => 'Short Sleeve', 'ar' => 'كم قصير'], 'type' => 'sleeve_type'],
            ['name' => ['en' => 'Three Quarter', 'ar' => 'ثلاثة أرباع'], 'type' => 'sleeve_type'],
        ];

        foreach ($options as $option) {
            DesignOption::create($option);
        }

        $this->command->info( count($options) . ' design options created');
    }
}
