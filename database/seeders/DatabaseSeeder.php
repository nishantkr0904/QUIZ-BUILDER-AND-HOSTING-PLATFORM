<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if it doesn't exist
        // Only create admin user in development environment
        if (app()->environment('local') && !DB::table('users')->where('email', 'admin@qbhp.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Admin User',
                'email' => 'admin@qbhp.com',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Run sample quiz seeder (which will create categories and quizzes)
        $this->call(SampleQuizzesSeeder::class);
    }
}
