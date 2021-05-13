<?php

namespace App\Payments;

use App\CustomerId;
use App\SubscriptionId;
use App\Models\Account\Team;

interface PaymentGateway
{

    public function createCustomerForTeam(Team $team): CustomerId;

    public function createBillingSessionForCustomer(CustomerId $id): string;

    public function subscribeCustomerToFreePlan(CustomerId $id): SubscriptionId;

}
