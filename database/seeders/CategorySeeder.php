<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $categories = [
        'Appetizers / Pulutan',
        'Main Dishes',
        'Pasta & Pizza',
        'Snacks / Bar Chow',
        'Alcoholic Beverages',
        'Cocktails',
        'Non-Alcoholic Drinks',
    ];

    foreach ($categories as $cat) {
        Category::firstOrCreate(['name' => $cat]);
    }
    }
}
