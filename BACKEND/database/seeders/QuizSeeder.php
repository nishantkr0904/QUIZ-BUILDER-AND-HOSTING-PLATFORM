<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs
        $programmingId = DB::table('categories')->where('slug', 'programming')->first()->id;
        $mathematicsId = DB::table('categories')->where('slug', 'mathematics')->first()->id;
        $scienceId = DB::table('categories')->where('slug', 'science')->first()->id;

        // Get admin user ID (assuming first user is admin)
        $adminId = DB::table('users')->first()->id;

        // Get admin user ID (assuming first user is admin)
        $adminId = DB::table('users')->first()->id;

        $quizzes = [
            [
                'title' => 'Programming Quiz 1',
                'slug' => 'programming-quiz-1',
                'category_id' => $programmingId,
                'description' => 'Test your knowledge in Programming',
                'instructions' => 'Read each question carefully before answering.',
                'duration' => 54,
                'pass_score' => 70,
                'status' => 'published',
                'is_featured' => true,
                'difficulty' => 'easy',
                'total_questions' => 20
            ],
            [
                'title' => 'Programming Quiz 2',
                'slug' => 'programming-quiz-2',
                'category_id' => $programmingId,
                'description' => 'Test your knowledge in Programming',
                'instructions' => 'Advanced programming concepts test.',
                'duration' => 29,
                'pass_score' => 75,
                'status' => 'published',
                'is_featured' => false,
                'difficulty' => 'hard',
                'total_questions' => 15
            ],
            [
                'title' => 'Mathematics Quiz 1',
                'slug' => 'mathematics-quiz-1',
                'category_id' => $mathematicsId,
                'description' => 'Test your knowledge in Mathematics',
                'instructions' => 'Basic mathematics concepts.',
                'duration' => 59,
                'pass_score' => 60,
                'status' => 'published',
                'is_featured' => true,
                'difficulty' => 'easy',
                'total_questions' => 25
            ],
            [
                'title' => 'Mathematics Quiz 2',
                'slug' => 'mathematics-quiz-2',
                'category_id' => $mathematicsId,
                'description' => 'Test your knowledge in Mathematics',
                'instructions' => 'Intermediate level mathematics.',
                'duration' => 39,
                'pass_score' => 65,
                'status' => 'published',
                'is_featured' => false,
                'difficulty' => 'medium',
                'total_questions' => 20
            ],
            [
                'title' => 'Science Quiz 1',
                'slug' => 'science-quiz-1',
                'category_id' => $scienceId,
                'description' => 'Test your knowledge in Science',
                'instructions' => 'General science concepts.',
                'duration' => 49,
                'pass_score' => 60,
                'status' => 'published',
                'is_featured' => true,
                'difficulty' => 'medium',
                'total_questions' => 30
            ],
            [
                'title' => 'Science Quiz 2',
                'slug' => 'science-quiz-2',
                'category_id' => $scienceId,
                'description' => 'Test your knowledge in Science',
                'instructions' => 'Advanced science topics.',
                'duration' => 50,
                'pass_score' => 70,
                'status' => 'published',
                'is_featured' => false,
                'difficulty' => 'medium',
                'total_questions' => 25
            ]
        ];

        foreach ($quizzes as $quiz) {
            DB::table('quizzes')->insert([
                'user_id' => $adminId,
                'title' => $quiz['title'],
                'slug' => $quiz['slug'],
                'category_id' => $quiz['category_id'],
                'description' => $quiz['description'],
                'instructions' => $quiz['instructions'],
                'duration' => $quiz['duration'],
                'pass_score' => $quiz['pass_score'],
                'attempts_allowed' => 2,
                'is_featured' => $quiz['is_featured'],
                'show_answers' => true,
                'status' => $quiz['status'],
                'total_questions' => $quiz['total_questions'],
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
