<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteInvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_authenticated_user_cannot_delete_an_invitation()
    {
        $this->withExceptionHandling();

        // Given
        $team = Team::factory()->create();
        $invitation = Invitation::factory()->create([
            'team_id' => $team->id,
        ]);

        // When
        $response = $this->delete(
            route('settings.teams.invitations.destroy', [
                'team' => $team->id,
                'invitation' => $invitation->id,
            ])
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_member_of_another_team_cannot_delete_an_invitation()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();
        $invitation = Invitation::factory()->create([
            'team_id' => $team->id,
        ]);

        // When
        $response = $this->delete(
            route('settings.teams.invitations.destroy', [
                'team' => $team->id,
                'invitation' => $invitation->id,
            ])
        );

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function a_team_member_cannot_delete_an_invitation()
    {
        //
    }

    /** @test */
    public function a_team_owner_can_delete_an_invitation()
    {
        //
    }

}
