<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role column (only admin, user, cashier)
            $table->enum('role', ['admin', 'user', 'cashier'])
                  ->default('user')
                  ->after('password');

            // Add status column
            $table->enum('status', ['active', 'inactive'])
                  ->default('active')
                  ->after('role');

            // Add username column
            $table->string('username')
                  ->nullable()
                  ->unique()
                  ->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'username']);
        });
    }
};
