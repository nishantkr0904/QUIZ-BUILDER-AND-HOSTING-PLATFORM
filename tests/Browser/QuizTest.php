<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QuizTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_user_can_see_quiz_listing()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['title' => 'Test Quiz']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                   ->visit('/quizzes')
                   ->assertSee('Test Quiz')
                   ->assertSee('Take Quiz');
        });
    }

    public function test_user_can_attempt_quiz()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create([
            'title' => 'Test Quiz',
            'duration' => 10,
            'category_id' => 1,
            'difficulty' => 'Easy',
            'passing_score' => 70,
        ]);

        $this->browse(function (Browser $browser) use ($user, $quiz) {
            $browser->loginAs($user)
                   ->visit('/quiz/' . $quiz->id)
                   ->assertSee('Test Quiz')
                   ->assertSee('Time Remaining:')
                   ->assertPresent('#quiz-form')
                   ->assertPresent('.quiz-timer');
            
            // Simulate answering questions
            $browser->radio('answers[1]', '1')
                   ->radio('answers[2]', '2')
                   ->press('Submit Quiz')
                   ->assertPathIs('/quiz/' . $quiz->id . '/result')
                   ->assertSee('Quiz Results');
        });
    }

    public function test_quiz_auto_submits_on_timer_expiry()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create([
            'title' => 'Timed Quiz',
            'duration' => 1, // 1 minute
            'category_id' => 1,
            'difficulty' => 'Easy',
            'passing_score' => 70
        ]);

        $this->browse(function (Browser $browser) use ($user, $quiz) {
            $browser->loginAs($user)
                   ->visit('/quiz/' . $quiz->id)
                   ->pause(61000) // Wait for 61 seconds
                   ->assertPathIs('/quiz/' . $quiz->id . '/result')
                   ->assertSee('Time Expired');
        });
    }
}
