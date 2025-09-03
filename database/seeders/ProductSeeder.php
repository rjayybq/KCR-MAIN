<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        // $appetizers = Category::create(['name' => 'Appetizers / Pulutan']);
        // $dishes     = Category::create(['name' => 'Main Dishes']);
        // $alcohols   = Category::create(['name' => 'Alcoholic Beverages']);

        // Create Products
        // $sisig = Product::create([
        //     'ProductName' => 'SISIG',
        //     'category_id' => $appetizers->id,
        //     'stock'       => 50,
        //     'price'       => 120,
        // ]);

        // $chicken = Product::create([
        //     'ProductName' => 'CHICKEN INASAL',
        //     'category_id' => $dishes->id,
        //     'stock'       => 50,
        //     'price'       => 120,
        // ]);

        // $redHorse = Product::create([
        //     'ProductName' => 'RED HORSE',
        //     'category_id' => $alcohols->id,
        //     'stock'       => 50,
        //     'price'       => 120,
        // ]);

        // // ✅ Automatically add initial Stock In records
        // $products = [$sisig, $chicken, $redHorse];

        // foreach ($products as $product) {
        //     Stock::create([
        //         'product_id' => $product->id,
        //         'type'       => 'in',
        //         'quantity'   => $product->stock,  // Same as initial stock
        //         'date'       => now()->toDateString(),
        //     ]);
        // }
    }
}
