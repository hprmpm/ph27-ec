<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daikon = new Product;
        $daikon->name = 'だいこん';
        $daikon->price = 200;
        $daikon->image='/product-images/daikon01.png';
        $daikon->save();

        $ninjin = new Product;
        $ninjin->name = 'にんじん';
        $ninjin->price = 80;
        $ninjin->image='/product-images/ninjin01.png';
        $ninjin->save();

        $kabocha = new Product;
        $kabocha->name = 'かぼちゃ';
        $kabocha->price = 300;
        $kabocha->image='/product-images/kabocha01.png';
        $kabocha->save();
    }
}
