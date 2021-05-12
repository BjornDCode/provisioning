<?php

namespace App\Payments;

use App\CustomerId;
use App\Models\Account\Team;

interface PaymentGateway
{

    public function createCustomerForTeam(Team $team): CustomerId;

}
