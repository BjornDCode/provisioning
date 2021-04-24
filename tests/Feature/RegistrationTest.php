<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account\Team;
use App\Models\Auth\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function it_creates_a_team_for_a_new_user()
    {
        // Given
        // When
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Then
        $user = User::first();
        $team = Team::first();
        $this->assertDatabaseHas('teams', [
            'name' => "Test User's Team",
            'owner_id' => $user->id,
        ]);
        $this->assertEquals($team->id, $user->current_team_id);
    }
}
