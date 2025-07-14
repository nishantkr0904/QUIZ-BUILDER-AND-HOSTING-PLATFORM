<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;

class QuizFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_attempt_and_submit_quiz()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['created_by' => $user->id]);
        $question = Question::factory()->create(['quiz_id' => $quiz->id, 'correct_answer' => 'A']);

        $this->actingAs($user);
        // Save answer
        $response = $this->postJson(route('ajax.quiz.saveAnswer', ['quizId' => $quiz->id]), [
            'question_id' => $question->id,
            'answer' => 'A',
        ]);
        $response->assertJson(['status' => 'saved']);

        // Submit quiz
        $response = $this->postJson(route('ajax.quiz.submit', ['quizId' => $quiz->id]));
        $response->assertJson(['status' => 'submitted']);
        $this->assertDatabaseHas('results', [
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => 1,
        ]);
    }
}
