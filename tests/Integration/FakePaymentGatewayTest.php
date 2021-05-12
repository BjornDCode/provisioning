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

    /** @test */
    public function it_can_subscribe_a_team()
    {
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        $team = Team::factory()->create();
        $plan = Plan::factory()->create([
            'team_id' => $team->id,
        ]);
        $gateway = $this->app->make(PaymentGateway::class);

        // When
        $gateway->subscribeCustomerToPlan(
            CustomerId::fromString($plan->customer_id),
        );

        // Then
        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'subscription_id' => 'fake_subscription_id_123',
            'expires_at' => null,
        ]);
    }

    /** @test */
    public function it_can_cancel_a_subscription()
    {
        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);

        // Given
        $team = Team::factory()->create();
        $plan = Plan::factory()->create([
            'team_id' => $team->id,
            'expires_at' => null,
        ]);
        $gateway = $this->app->make(PaymentGateway::class);

        // When
        $gateway->cancelSubscription(
            SubscriptionId::fromString($plan->subscription_id),
        );

        // Then
        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'expires_at' => Carbon::now()->addWeeks(2),
        ]);
    }

}
