<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateQuestionOrderSeeder extends Seeder
{
    public function run()
    {
        $quizzes = DB::table('quizzes')->get();
        
        foreach ($quizzes as $quiz) {
            $questions = DB::table('questions')
                ->where('quiz_id', $quiz->id)
                ->orderBy('id')
                ->get();
                
            $order = 1;
            foreach ($questions as $question) {
                DB::table('questions')
                    ->where('id', $question->id)
                    ->update(['order' => $order++]);
            }
        }
    }
}
