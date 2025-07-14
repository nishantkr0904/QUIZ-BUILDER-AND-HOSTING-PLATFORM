<?php
namespace Database\Factories;

use App\Models\Quiz;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'category_id' => Category::factory(),
            'difficulty' => $this->faker->randomElement(['Easy', 'Medium', 'Hard']),
            'duration' => $this->faker->numberBetween(5, 30),
            'passing_score' => $this->faker->numberBetween(5, 10),
            'review_enabled' => $this->faker->boolean(),
            'created_by' => User::factory(),
        ];
    }
}
