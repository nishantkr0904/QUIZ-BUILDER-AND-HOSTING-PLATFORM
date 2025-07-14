<?php
namespace App\Services;

use App\Models\Quiz;
use App\Models\Question;

class QuizBuilderService
{
    /**
     * Create a new quiz with questions.
     * @param array $quizData
     * @param array $questionsData
     * @return Quiz
     */
    public function createQuizWithQuestions(array $quizData, array $questionsData)
    {
        $quiz = Quiz::create($quizData);
        foreach ($questionsData as $qData) {
            $quiz->questions()->create($qData);
        }
        return $quiz;
    }

    /**
     * Update quiz and its questions.
     * @param Quiz $quiz
     * @param array $quizData
     * @param array $questionsData
     * @return Quiz
     */
    public function updateQuizWithQuestions(Quiz $quiz, array $quizData, array $questionsData)
    {
        $quiz->update($quizData);
        // For simplicity, delete and recreate questions (can be optimized)
        $quiz->questions()->delete();
        foreach ($questionsData as $qData) {
            $quiz->questions()->create($qData);
        }
        return $quiz;
    }
}
