<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\Account\Team;
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

}
