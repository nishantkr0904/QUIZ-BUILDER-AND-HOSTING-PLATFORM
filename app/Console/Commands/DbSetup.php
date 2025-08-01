<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Facades\Hash;

class DbSetup extends Command
{
    protected $signature = 'db:setup';
    protected $description = 'Set up the database with initial data';

    public function handle()
    {
        $this->info('Creating admin user...');
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        $this->info('Creating regular users...');
        User::factory(5)->create();

        $this->info('Creating categories and quizzes...');
        $categories = ['Programming', 'Mathematics', 'Science'];
        foreach ($categories as $categoryName) {
            $category = Category::create([
                'name' => $categoryName,
                'description' => "Quizzes about {$categoryName}",
            ]);

            for ($i = 1; $i <= 2; $i++) {
                $quiz = Quiz::create([
                    'title' => "{$categoryName} Quiz {$i}",
                    'description' => "Test your knowledge in {$categoryName}",
                    'category_id' => $category->id,
                    'created_by' => $admin->id,
                    'status' => 'published',
                    'featured' => $i === 1,
                    'difficulty' => ['easy', 'medium', 'hard'][rand(0, 2)],
                    'duration' => rand(15, 60),
                    'passing_score' => 60,
                    'published_at' => now(),
                ]);

                for ($j = 1; $j <= 5; $j++) {
                    Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => "Question {$j} for {$categoryName} Quiz {$i}",
                        'question_type' => 'multiple_choice',
                        'options' => json_encode([
                            'A' => 'Option A',
                            'B' => 'Option B',
                            'C' => 'Option C',
                            'D' => 'Option D'
                        ]),
                        'correct_answer' => 'A',
                        'points' => 1,
                        'order' => $j,
                    ]);
                }
            }
        }

        $this->info('Database setup completed successfully!');
    }
}
