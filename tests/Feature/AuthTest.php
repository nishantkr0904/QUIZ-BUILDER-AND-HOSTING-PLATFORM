<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->post('/logout');
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }
}
