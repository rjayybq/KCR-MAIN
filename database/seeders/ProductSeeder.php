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
            $appetizers = Category::create(['name' => 'Appetizers / Pulutan']);
            $dishes     = Category::create(['name' => 'Main Dishes']);
            $alcohols     = Category::create(['name' => 'Alcoholic Beverages']);

        Product::create([
            'ProductName' => 'SISIG',
            'category_id' => $appetizers->id,  // ✅ category_id not category
            'weight'      => 1.5,
            'unit'        => 'kg',
            'stock'       => 50,
            'price'       => 120,
        ]);

       Product::create([
            'ProductName' => 'CHICKEN INASAL',
            'category_id' => $dishes->id,  // ✅ category_id not category
            'weight'      => 1.5,
            'unit'        => 'kg',
            'stock'       => 50,
            'price'       => 120,
        ]);

       Product::create([
            'ProductName' => 'RED HORSE',
            'category_id' => $alcohols->id,  // ✅ category_id not category
            'weight'      => 1.5,
            'unit'        => 'kg',
            'stock'       => 50,
            'price'       => 120,
        ]);
    }
}
