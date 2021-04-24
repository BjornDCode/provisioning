<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Membership;
use App\Exceptions\ExistingMemberException;
use App\Exceptions\ExistingInvitationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_check_whether_the_owner_is_a_member_of_the_team()
    {
        // Given
        $user = User::factory()->create();
        $team = Team::factory()->create([
            'owner_id' => $user->id,
        ]);

        // When
        // Then
        $this->assertTrue(
            $team->hasMember($user)
        );
    }

    /** @test */
    public function it_can_whether_a_user_is_a_member_of_the_team()
    {
        // Given
        $member = User::factory()->create();
        $nonMember = User::factory()->create();
        $team = Team::factory()->create();

        Membership::factory()->create([
            'user_id' => $member->id,
            'team_id' => $team->id,
        ]);

        // When
        // Then
        $this->assertTrue(
            $team->hasMember($member)
        );
        $this->assertFalse(
            $team->hasMember($nonMember)
        );
    }

    /** @test */
    public function a_user_can_join_a_team()
    {
        // Given
        $user = User::factory()->create();
        $team = Team::factory()->create();

        // When
        $team->join($user);

        // Then
        $this->assertTrue(
            $team->hasMember($user)
        );
    }

    /** @test */
    public function a_person_can_be_invited_via_email()
    {
        // Given
        $email = 'test@example.com';
        $team = Team::factory()->create();

        // When
        $team->invite($email);

        // Then
        $this->assertDatabaseHas('invitations', [
            'team_id' => $team->id,
            'email' => $email,
        ]);
    }

    /** @test */
    public function a_user_cannot_be_invited_if_they_are_already_a_member_of_the_team()
    {
        // Then
        $this->expectException(ExistingMemberException::class);

        // Given
        $email = 'test@example.com';
        $user = User::factory()->create([
            'email' => $email,
        ]);
        $team = Team::factory()->create();
        $team->join($user);

        // When
        $team->invite($email);

    }

    /** @test */
    public function a_person_cannot_be_invited_if_they_have_already_been_invited()
    {
        // Then
        $this->expectException(ExistingInvitationException::class);

        // Given
        $email = 'test@example.com';
        $team = Team::factory()->create();
        $team->invite($email);

        // When
        $team->invite($email);
    }

    /** @test */
    public function it_can_check_whether_a_person_has_been_invited()
    {
        // Given
        $invitedEmail = 'invited@example.com';
        $notInvitedEmail = 'not-invited@example.com';
        $team = Team::factory()->create();

        Invitation::factory()->create([
            'email' => $invitedEmail,
            'team_id' => $team->id,
        ]);

        // When
        // Then
        $this->assertTrue(
            $team->hasInvitation($invitedEmail)
        );
        $this->assertFalse(
            $team->hasInvitation($notInvitedEmail)
        );
    }

}
