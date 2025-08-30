<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CashierUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::updateOrCreate(
            ['email' => 'cashier@example.com'],
            [
                'name' => 'Cashier Account',
                'username' => 'cashier1',
                'password' => Hash::make('cashier123'),
                'role' => 'cashier',
                'status' => 'active',
            ]
        );
    }
}
