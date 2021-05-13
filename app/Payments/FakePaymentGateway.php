<?php

namespace App\Payments;

use Carbon\Carbon;
use App\CustomerId;
use App\SubscriptionId;
use App\Models\Account\Team;
use App\Models\Billing\Plan;
use App\Payments\PaymentGateway;

class FakePaymentGateway implements PaymentGateway
{

    public function createCustomerForTeam(Team $team): CustomerId
    {
        return CustomerId::fromString('fake_customer_id_123');
    }

    public function createBillingSessionForCustomer(CustomerId $id): string
    {
        return 'https://billing.stripe.com/session/123';
    }

    public function subscribeCustomerToFreePlan(CustomerId $id): SubscriptionId
    {
        $plan = Plan::where('customer_id', $id->toString())->first();

        $plan->update([
            'subscription_id' => 'fake_subscription_id_123',
        ]);

        return SubscriptionId::fromString('fake_subscription_id_123');
    }
    
}
