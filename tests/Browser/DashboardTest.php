<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_user_can_view_dashboard()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                   ->visit('/dashboard')
                   ->assertSee('Dashboard')
                   ->assertSee('My Quizzes')
                   ->assertSee('Available Quizzes');
        });
    }

    public function test_user_can_view_quiz_history()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                   ->visit('/my-quizzes')
                   ->assertSee('Quiz History')
                   ->assertPresent('.quiz-history-table');
        });
    }

    public function test_admin_dashboard_has_management_options()
    {
        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                   ->visit('/admin/dashboard')
                   ->assertSee('Admin Dashboard')
                   ->assertSee('Manage Quizzes')
                   ->assertSee('User Management')
                   ->assertSee('View Results');
        });
    }
}
