<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Models\Membership;
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

}
