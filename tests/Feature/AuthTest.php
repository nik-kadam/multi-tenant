<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_signup_page_is_accessible()
    {
        $response = $this->get('/signup');
        $response->assertStatus(200);
        $response->assertViewIs('signup');
    }

    public function test_user_can_signup()
    {
        $userData = [
            'name' => 'Test Tenant',
            'email' => 'tenant@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/tenant-signup', $userData);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', ['email' => 'tenant@example.com']);
        $this->assertAuthenticated();
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/tenant-login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/tenant-login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['success' => false]);

        $this->assertGuest();
    }
}
