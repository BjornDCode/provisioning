<?php

namespace Tests\Integration;

use Carbon\Carbon;
use App\CustomerId;
use Tests\TestCase;
use App\SubscriptionId;
use App\Models\Account\Team;
use App\Models\Billing\Plan;
use App\Payments\PaymentGateway;
use App\Payments\FakePaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StripePaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_customer_for_a_team()
    {
        // Given
        $team = Team::factory()->create();
        $gateway = $this->app->make(PaymentGateway::class);

        // When
        $customerId = $gateway->createCustomerForTeam($team);

        // Then
        $this->assertInstanceOf(CustomerId::class, $customerId);
    }

    /** @test */
    public function it_can_create_a_checkout_session_url()
    {
        $gateway = $this->app->make(PaymentGateway::class);

        // Given
        $team = Team::factory()->create();
        $customerId = $gateway->createCustomerForTeam($team);
        $plan = Plan::factory()->create([
            'team_id' => $team->id,
            'customer_id' => $customerId->toString(),
        ]);

        // When
        $url = $gateway->createBillingSessionForCustomer(
            CustomerId::fromString($plan->customer_id)
        );

        // Then
        $this->assertStringContainsString('https://billing.stripe.com/session', $url);
    }

    /** @test */
    public function it_can_subscribe_a_team_to_the_free_plan()
    {
        $gateway = $this->app->make(PaymentGateway::class);

        // Given
        $team = Team::factory()->create();
        $customerId = $gateway->createCustomerForTeam($team);
        $plan = Plan::factory()->create([
            'team_id' => $team->id,
            'customer_id' => $customerId->toString(),
        ]);

        // When
        $subscriptionId = $gateway->subscribeCustomerToFreePlan(
            CustomerId::fromString($plan->customer_id),
        );

        // Then
        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'subscription_id' => $subscriptionId->toString(),
        ]);
    }

}
