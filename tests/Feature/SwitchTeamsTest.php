<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SwitchTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_all_the_users_teams()
    {
        // Given
        $user = $this->registerNewUser();
        $membershipTeam = Team::factory()->create();

        $membershipTeam->join($user);

        // When
        $response = $this->get(route('dashboard'));

        // Then
        $response->assertInertia(function (Assert $page) use ($user, $membershipTeam) {
            $page->has('teams');

            $page->has('teams.0', function (Assert $team) use ($user) {
                $team->where('id', $user->currentTeam->id)
                     ->where('name', $user->currentTeam->name);
            });
            $page->has('teams.1', function (Assert $team) use ($membershipTeam) {
                $team->where('id', $membershipTeam->id)
                     ->where('name', $membershipTeam->name);
            });
        });
    }

    /** @test */
    public function a_non_authenticated_user_cannot_switch_to_teams()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_user_cannot_switch_to_teams_they_are_not_a_member_of()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_user_can_switch_to_teams_they_are_a_member_of()
    {
        $this->markTestIncomplete();
    }

}
