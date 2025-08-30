<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // unique email for admin
            [
                'name' => 'System Administrator',
                'username' => 'admin', // if you have a username field
                'password' => Hash::make('admin123'), // secure password
                'role' => 'admin', // make sure you added role column
                'status' => 'active', // optional, if you have status column
            ]
        );
    }
}
