<?php

namespace Database\Seeders;

use App\Models\Size as ModelsSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Size extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ['XS','S','M','L','XL','XXL'];
        foreach($items as $i) ModelsSize::create(['name'=>$i]);

    }
}
