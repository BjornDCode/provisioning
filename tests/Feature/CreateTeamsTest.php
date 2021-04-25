<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account\Team;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_authenticated_user_cannot_create_teams()
    {
        $this->withExceptionHandling();

        // Given
        // When 
        $response = $this->post(route('settings.teams.store'));

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_create_teams()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('settings.teams.store'),
            [
                'name' => 'New team',
            ]
        );

        // Then
        $team = Team::where('name', 'New team')->first();
        $this->assertDatabaseHas('teams', [
            'name' => 'New team'
        ]);
        $response->assertRedirect(
            route('settings.teams.show', [ 'team' => $team->id, ])
        );
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(route('settings.teams.index'))
            ->post(
                route('settings.teams.store'),
                []
            );

        // Then
        $response->assertRedirect(
            route('settings.teams.index')
        );
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_sets_the_users_current_team_to_the_new_team()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('settings.teams.store'),
            [
                'name' => 'New team',
            ]
        );

        // Then
        $team = Team::where('name', 'New team')->first();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'current_team_id' => $team->id,
        ]);
    }

}
