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
        $daikon->save();

        $ninjin = new Product;
        $ninjin->name = 'にんじん';
        $ninjin->price = 80;
        $ninjin->save();

        $kabocha = new Product;
        $kabocha->name = 'かぼちゃ';
        $kabocha->price = 300;
        $kabocha->save();
    }
}
