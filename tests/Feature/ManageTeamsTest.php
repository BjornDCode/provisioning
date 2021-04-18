<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_authenticated_user_cannot_see_the_teams_page()
    {
        $this->withExceptionHandling();

        $response = $this->get(route('settings.teams.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_can_render_the_teams_page()
    {
        // Given
        $user = $this->registerNewUser();

        $membershipTeam = Team::factory()->create();
        $membershipTeam->join($user);

        // When
        $response = $this->get(route('settings.teams.index'));

        // Then
        $response->assertInertia(function (Assert $page) use ($user, $membershipTeam) {
            $page->is('Account/Teams/Index');
            $page->has('teams');
            $page->has('memberships');


            $page->has('teams.0', function (Assert $team) use ($user) {
                $team->where('id', $user->currentTeam->id)
                     ->where('name', $user->currentTeam->name);
            });
            $page->has('memberships.0', function (Assert $team) use ($membershipTeam) {
                $team->where('id', $membershipTeam->id)
                     ->where('name', $membershipTeam->name);
            });
        });
    }

    /** @test */
    public function a_non_authenticated_user_cannot_see_the_single_team_settings_page()
    {
        $this->markTestIncomplete();
        //
    }

    /** @test */
    public function a_user_cannot_see_team_settings_page_for_a_team_they_are_not_a_member_of()
    {
        $this->markTestIncomplete();
        //
    }

    /** @test */
    public function it_can_render_the_single_team_settings_page()
    {
        // List Members
        // List invitations
        $this->markTestIncomplete();
    }
}
