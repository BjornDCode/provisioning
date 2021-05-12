<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\TeamCreated;
use App\Models\Account\Team;
use App\Payments\PaymentGateway;
use App\Payments\FakePaymentGateway;
use Illuminate\Support\Facades\Event;
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
        Event::fake([
            TeamCreated::class,
        ]);

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
        Event::assertDispatched(TeamCreated::class, function ($event) use ($team) {
            return $event->team->id === $team->id;
        });
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

    /** @test */
    public function it_creates_a_plan_for_a_new_team()
    {
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create([
            'owner_id' => $user->id,
        ]);

        // When
        TeamCreated::dispatch($team);

        // Then
        $this->assertDatabaseHas('plans', [
            'team_id' => $team->id,
            'customer_id' => 'fake_customer_id_123',
        ]);
    }

}
