<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        if (!DB::table('users')->where('email', 'admin@qbhp.com')->exists()) { 
            DB::table('users')->insert([
                'name' => 'Admin User',
                'email' => 'admin@qbhp.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Run CategorySeeder first
        $this->call(CategorySeeder::class);
    }
}
