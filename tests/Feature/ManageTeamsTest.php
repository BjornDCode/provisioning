<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account\Team;
use App\Models\Auth\User;
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
            $page->has('owned');
            $page->has('memberships');


            $page->has('owned.0', function (Assert $team) use ($user) {
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
        $this->withExceptionHandling();

        // Given
        $team = Team::factory()->create();

        // When
        $response = $this->get(route('settings.teams.show', [ 'team' => $team->id ]));

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_cannot_see_team_settings_page_for_a_team_they_are_not_an_owner_of()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();
        $team->join($user);

        // When
        $response = $this->get(route('settings.teams.show', [ 'team' => $team->id ]));

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_render_the_single_team_settings_page()
    {
        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create([
            'owner_id' => $user->id,
        ]);

        $member = User::factory()->create();
        $team->join($member);
        $invitation = $team->invite('test@example.com');

        // When
        $response = $this->get(route('settings.teams.show', [ 'team' => $team->id ]));

        // Then
        $response->assertInertia(function (Assert $page) use ($member, $invitation) {
            $page->is('Account/Teams/Show');
            $page->has('members');
            $page->has('invitations');


            $page->has('members.0', function (Assert $resource) use ($member) {
                $resource->where('id', $member->id)
                         ->where('name', $member->name)
                         ->where('email', $member->email);
            });
            $page->has('invitations.0', function (Assert $resource) use ($invitation) {
                $resource->where('id', $invitation->id)
                         ->where('email', $invitation->email);
            });
        });
    }
}
