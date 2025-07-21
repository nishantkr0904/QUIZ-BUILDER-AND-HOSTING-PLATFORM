<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password', 'password123')
                   ->type('password_confirmation', 'password123')
                   ->press('Register')
                   ->assertPathIs('/dashboard')
                   ->assertSee('Welcome');
        });
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                   ->type('email', 'test@example.com')
                   ->type('password', 'password123')
                   ->press('Login')
                   ->assertPathIs('/dashboard')
                   ->assertSee('Welcome');
        });
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                   ->visit('/dashboard')
                   ->click('#logout-form button')
                   ->assertPathIs('/login');
        });
    }
}
