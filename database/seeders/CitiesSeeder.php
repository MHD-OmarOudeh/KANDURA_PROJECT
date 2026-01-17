<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['ar' => 'دمشق',        'en' => 'Damascus'],
            ['ar' => 'ريف دمشق',    'en' => 'Rif Dimashq'],
            ['ar' => 'حلب',         'en' => 'Aleppo'],
            ['ar' => 'حمص',         'en' => 'Homs'],
            ['ar' => 'حماة',        'en' => 'Hama'],
            ['ar' => 'اللاذقية',    'en' => 'Latakia'],
            ['ar' => 'طرطوس',       'en' => 'Tartus'],
            ['ar' => 'إدلب',        'en' => 'Idlib'],
            ['ar' => 'الرقة',       'en' => 'Raqqa'],
            ['ar' => 'دير الزور',   'en' => 'Deir ez-Zor'],
            ['ar' => 'الحسكة',      'en' => 'Hasakah'],
            ['ar' => 'السويداء',    'en' => 'As-Suwayda'],
            ['ar' => 'درعا',        'en' => 'Daraa'],
            ['ar' => 'القنيطرة',    'en' => 'Quneitra'],
        ];

        foreach ($cities as $city) {
            City::create([
                'name' => [
                    'ar' => $city['ar'],
                    'en' => $city['en'],
                ]
            ]);
        }
    }
}
