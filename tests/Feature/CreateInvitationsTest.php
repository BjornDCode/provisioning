<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account\Team;
use App\Models\Auth\User;
use App\Mail\Invited;
use Illuminate\Support\Facades\Mail;
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
            route('settings.teams.invitations.store', [ 'team' => $team->id, ]),
            [
                'email' => 'test@example.com',
            ]
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_member_of_another_team_cannot_create_an_invite()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();

        // When
        $response = $this->post(
            route('settings.teams.invitations.store', [ 'team' => $team->id, ]),
            [
                'email' => 'test@example.com',
            ]
        );

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function a_member_of_the_team_cannot_create_an_invite()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();
        $team->join($user);

        // When
        $response = $this->post(
            route('settings.teams.invitations.store', [ 'team' => $team->id, ]),
            [
                'email' => 'test@example.com',
            ]
        );

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function a_team_owner_can_create_an_invite()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('settings.teams.invitations.store', [ 'team' => $user->currentTeam->id, ]),
            [
                'email' => 'test@example.com',
            ]
        );

        // Then
        $this->assertDatabaseHas('invitations', [
            'team_id' => $user->currentTeam->id,
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(
            route('settings.teams.show', [ 'team' => $user->currentTeam->id ])
        );
        $response->assertSessionHas('message', 'test@example.com was invited!');
    }

    /** @test */
    public function it_sends_an_email_invitation()
    {
        Mail::fake();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('settings.teams.invitations.store', [ 'team' => $user->currentTeam->id, ]),
            [
                'email' => 'test@example.com',
            ]
        );

        // Then
        Mail::assertQueued(Invited::class);
    }

    /** @test */
    public function email_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(
                route('settings.teams.show', [ 'team' => $user->currentTeam->id, ])
            )
            ->post(
                route('settings.teams.invitations.store', [ 'team' => $user->currentTeam->id, ]),
                []
            );

        // Then
        $response->assertRedirect(
            route('settings.teams.show', [ 'team' => $user->currentTeam->id, ])
        );
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_person_cannot_be_invited_if_they_have_already_been_invited()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $user->currentTeam->invite('test@example.com');

        // When
        $response = $this
            ->from(
                route('settings.teams.show', [ 'team' => $user->currentTeam->id, ])
            )
            ->post(
                route('settings.teams.invitations.store', [ 'team' => $user->currentTeam->id, ]),
                [
                    'email' => 'test@example.com',
                ]
            );

        // Then
        $response->assertRedirect(
            route('settings.teams.show', [ 'team' => $user->currentTeam->id, ])
        );
        $response->assertSessionHasErrors([
            'email' => 'An invitation already exists for this email.'
        ]);
    }

    /** @test */
    public function a_user_cannot_be_invited_if_they_are_already_a_member_of_the_team()
    {
        $this->withExceptionHandling();
        
        // Given
        $user = $this->registerNewUser();
        $member = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $user->currentTeam->join($member);

        // When
        $response = $this
            ->from(
                route('settings.teams.show', [ 'team' => $user->currentTeam->id, ])
            )
            ->post(
                route('settings.teams.invitations.store', [ 'team' => $user->currentTeam->id, ]),
                [
                    'email' => 'test@example.com',
                ]
            );

        // Then
        $response->assertRedirect(
            route('settings.teams.show', [ 'team' => $user->currentTeam->id, ])
        );
        $response->assertSessionHasErrors([
            'email' => 'The user is already a team member.'
        ]);
    }
}
