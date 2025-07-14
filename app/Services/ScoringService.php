<?php
namespace App\Services;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\Question;

class ScoringService
{
    /**
     * Calculate the score for a quiz attempt.
     * @param Quiz $quiz
     * @param array $answers (question_id => user_answer)
     * @return array [score => int, details => array]
     */
    public function calculateScore(Quiz $quiz, array $answers)
    {
        $score = 0;
        $details = [];
        foreach ($quiz->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer !== null && $userAnswer == $question->correct_answer;
            if ($isCorrect) {
                $score += $question->points;
            }
            $details[] = [
                'question_id' => $question->id,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'points' => $question->points,
            ];
        }
        return ['score' => $score, 'details' => $details];
    }
}
