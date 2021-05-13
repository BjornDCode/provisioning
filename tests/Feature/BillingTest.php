<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account\Team;
use App\Models\Billing\Plan;
use App\Payments\PaymentGateway;
use App\Payments\FakePaymentGateway;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_cannot_access_billing()
    {
        $this->withExceptionHandling();
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        // When
        $response = $this->get(route('settings.billing.show'));

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function team_members_cannot_access_billing()
    {
        $this->withExceptionHandling();
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        $user = $this->registerNewUser();
        $team = Team::factory()->create();

        $team->join($user);
        $user->setCurrentTeam($team);

        // When
        $response = $this->get(route('settings.billing.show'));

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function team_owners_can_access_billing()
    {
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        $user = $this->registerNewUser();
        Plan::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this->get(route('settings.billing.show'));

        // Then
        $response->assertStatus(409);
        $this->assertEquals(
            'https://billing.stripe.com/session/123',
            $response->headers->all('x-inertia-location')[0]
        );
    }

}
