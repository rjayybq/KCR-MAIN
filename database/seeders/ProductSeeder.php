<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $vegetables = Category::create(['name' => 'Vegetables']);
            $fruits     = Category::create(['name' => 'Fruits']);
            $grains     = Category::create(['name' => 'Grains']);

        Product::create([
            'ProductName' => 'CARROT',
            'category_id' => $vegetables->id,  // ✅ category_id not category
            'weight'      => 1.5,
            'unit'        => 'kg',
            'stock'       => 50,
            'price'       => 120,
        ]);

       Product::create([
            'ProductName' => 'APPLE',
            'category_id' => $vegetables->id,  // ✅ category_id not category
            'weight'      => 1.5,
            'unit'        => 'kg',
            'stock'       => 50,
            'price'       => 120,
        ]);

       Product::create([
            'ProductName' => 'BANANA',
            'category_id' => $vegetables->id,  // ✅ category_id not category
            'weight'      => 1.5,
            'unit'        => 'kg',
            'stock'       => 50,
            'price'       => 120,
        ]);
    }
}
