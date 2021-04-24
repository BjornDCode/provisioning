<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateInvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_authenticated_user_cannot_create_an_invite()
    {
        $this->withExceptionHandling();
        
        // Given
        $team = Team::factory()->create();

        // When
        $response = $this->post(
            route('settings.teams.invitations.store', [ 'team' => $team->id, ])
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_member_of_another_team_cannot_create_an_invite()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_member_of_the_team_cannot_create_an_invite()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_team_owner_can_create_an_invite()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_person_cannot_be_invited_if_they_have_already_been_invited()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_user_cannot_be_invited_if_they_are_already_a_member_of_the_team()
    {
        $this->markTestIncomplete();
    }


    /** @test */
    public function email_is_required()
    {
        $this->markTestIncomplete();
    }
}
