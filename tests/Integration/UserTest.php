<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\Account\Team;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_the_current_team_for_a_user()
    {
        // Given
        $user = User::factory()->create();
        $team = Team::factory()->create();

        // When
        $user->setCurrentTeam($team);

        // Then
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'current_team_id' => $team->id,
        ]);
    }

}
