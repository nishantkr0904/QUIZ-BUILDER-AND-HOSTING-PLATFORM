<?php
namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        $type = $this->faker->randomElement(['MCQ', 'single', 'true_false']);
        $options = $type === 'MCQ' ? [
            'A' => $this->faker->word(),
            'B' => $this->faker->word(),
            'C' => $this->faker->word(),
            'D' => $this->faker->word(),
        ] : null;
        return [
            'quiz_id' => Quiz::factory(),
            'type' => $type,
            'question_text' => $this->faker->sentence(),
            'options' => $options,
            'correct_answer' => $type === 'true_false' ? $this->faker->randomElement(['true', 'false']) : 'A',
            'points' => 1,
        ];
    }
}
