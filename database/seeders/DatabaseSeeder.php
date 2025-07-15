<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);
        \App\Models\User::factory(5)->create();
        \App\Models\Category::factory(3)->create()->each(function ($category) {
            \App\Models\Quiz::factory(2)->create(['category_id' => $category->id])->each(function ($quiz) {
                \App\Models\Question::factory(5)->create(['quiz_id' => $quiz->id]);
            });
        });
    }
}
