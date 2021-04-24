<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcceptInvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_existing_user_can_accept_an_invitation()
    {
        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();

        $invitation = $team->invite($user->email);

        // When
        $response = $this->get(
            route('settings.teams.memberships.store', [
                'team' => $team->id,
                'token' => $invitation->token,
            ])
        );

        // Then
        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'current_team_id' => $team->id,
        ]);
        
        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function a_new_user_have_to_create_an_account_before_accepting_the_invite()
    {
        $this->withExceptionHandling();

        // Given
        $team = Team::factory()->create();

        $invitation = $team->invite('test@example.com');

        // When
        $response = $this->get(
            route('settings.teams.memberships.store', [
                'team' => $team->id,
                'token' => $invitation->token,
            ])
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function the_token_has_to_exist()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();

        $invitation = $team->invite($user->email);

        // When
        $response = $this->get(
            route('settings.teams.memberships.store', [
                'team' => $team->id,
                'token' => 'fake-token',
            ])
        );

        // Then
        $this->assertDatabaseMissing('memberships', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function the_token_has_to_match_the_team()
    {
        $this->withExceptionHandling();
        
        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();
        $anotherTeam = Team::factory()->create();

        $invitation = $team->invite($user->email);

        // When
        $response = $this->get(
            route('settings.teams.memberships.store', [
                'team' => $anotherTeam->id,
                'token' => $invitation->token,
            ])
        );

        // Then
        $this->assertDatabaseMissing('memberships', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
        ]);

        $response->assertStatus(404);
    }

}
