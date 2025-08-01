<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Test your programming knowledge across different languages and concepts.',
            ],
            [
                'name' => 'Mathematics',
                'slug' => 'mathematics',
                'description' => 'Challenge yourself with mathematical problems and concepts.',
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Explore various scientific concepts and theories.',
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
