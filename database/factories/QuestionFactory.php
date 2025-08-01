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
        $type = $this->faker->randomElement(['multiple_choice', 'true_false', 'single_answer']);
        $options = $type === 'multiple_choice' ? [
            'A' => $this->faker->word(),
            'B' => $this->faker->word(),
            'C' => $this->faker->word(),
            'D' => $this->faker->word(),
        ] : ($type === 'true_false' ? ['True', 'False'] : null);
        
        return [
            'quiz_id' => Quiz::factory(),
            'question_type' => $type,
            'question_text' => $this->faker->sentence(),
            'options' => $options,
            'correct_answer' => $type === 'true_false' ? 'True' : 'A',
            'explanation' => $this->faker->paragraph(),
            'order' => $this->faker->numberBetween(1, 10),
            'points' => 1,
        ];
    }
}
