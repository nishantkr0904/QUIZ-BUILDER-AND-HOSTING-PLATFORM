<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_admin_can_create_quiz()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                   ->visit('/admin/quizzes/create')
                   ->type('title', 'New Test Quiz')
                   ->type('description', 'This is a test quiz')
                   ->type('time_limit', '30')
                   ->check('is_published')
                   ->press('Create Quiz')
                   ->assertPathIs('/admin/quizzes')
                   ->assertSee('New Test Quiz')
                   ->assertSee('Quiz created successfully');
        });
    }

    public function test_admin_can_manage_questions()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);
        $quiz = Quiz::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $quiz) {
            $browser->loginAs($admin)
                   ->visit('/admin/quizzes/' . $quiz->id . '/questions')
                   ->click('#add-question')
                   ->type('question_text', 'What is Laravel?')
                   ->type('options[0]', 'A PHP Framework')
                   ->type('options[1]', 'A JavaScript Library')
                   ->type('options[2]', 'A Database')
                   ->type('options[3]', 'An Operating System')
                   ->radio('correct_option', '0')
                   ->press('Add Question')
                   ->assertSee('Question added successfully')
                   ->assertSee('What is Laravel?');
        });
    }

    public function test_admin_can_view_quiz_results()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                   ->visit('/admin/results')
                   ->assertSee('Quiz Results')
                   ->assertPresent('.results-table');
        });
    }
}
