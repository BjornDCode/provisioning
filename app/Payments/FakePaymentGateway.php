<?php

namespace App\Payments;

use App\CustomerId;
use App\Models\Account\Team;
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
    
}
