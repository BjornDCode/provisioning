<?php

namespace Tests\Integration;

use App\CustomerId;
use Tests\TestCase;
use App\Models\Account\Team;
use App\Models\Billing\Plan;
use App\Payments\PaymentGateway;
use App\Payments\FakePaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FakePaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_customer_for_a_team()
    {
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        $team = Team::factory()->create();
        $gateway = $this->app->make(PaymentGateway::class);

        // When
        $customerId = $gateway->createCustomerForTeam($team);

        // Then
        $this->assertEquals('fake_customer_id_123', $customerId->toString());
    }

    /** @test */
    public function it_can_create_a_checkout_session_url()
    {
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        $team = Team::factory()->create();
        $plan = Plan::factory()->create([
            'team_id' => $team->id,
        ]);
        $gateway = $this->app->make(PaymentGateway::class);

        // When
        $url = $gateway->createBillingSessionForCustomer(
            CustomerId::fromString($plan->customer_id)
        );

        // Then
        $this->assertEquals('https://billing.stripe.com/session/123', $url);
    }

}
