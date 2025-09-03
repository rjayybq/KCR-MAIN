<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        // Stock::truncate();

        // $today = Carbon::today();

        // // Get product IDs from your seeded products
        // $sisig   = Product::where('ProductName', 'SISIG')->first()->id ?? null;
        // $inasal  = Product::where('ProductName', 'CHICKEN INASAL')->first()->id ?? null;
        // $redhorse = Product::where('ProductName', 'RED HORSE')->first()->id ?? null;

        // $stocks = [
        //     // SISIG
        //     ['product_id' => $sisig, 'type' => 'in', 'quantity' => 20, 'date' => $today->copy()->subDays(5)],
        //     ['product_id' => $sisig, 'type' => 'out', 'quantity' => 5,  'date' => $today->copy()->subDays(3)],

        //     // CHICKEN INASAL
        //     ['product_id' => $inasal, 'type' => 'in', 'quantity' => 30, 'date' => $today->copy()->subDays(4)],
        //     ['product_id' => $inasal, 'type' => 'out', 'quantity' => 10, 'date' => $today->copy()->subDays(2)],

        //     // RED HORSE
        //     ['product_id' => $redhorse, 'type' => 'in', 'quantity' => 50, 'date' => $today->copy()->subDays(6)],
        //     ['product_id' => $redhorse, 'type' => 'out', 'quantity' => 12, 'date' => $today->copy()->subDays(1)],
        // ];

        // foreach ($stocks as $stock) {
        //     if ($stock['product_id']) {
        //         Stock::create($stock);
        //     }
        // }
    }
}
