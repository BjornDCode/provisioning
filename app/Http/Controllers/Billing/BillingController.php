<?php

namespace App\Http\Controllers\Billing;

use App\CustomerId;
use Inertia\Inertia;
use App\Payments\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    
    public function show(PaymentGateway $gateway)
    {
        $this->authorize('update', Auth::user()->currentTeam);

        $url = $gateway->createBillingSessionForCustomer(
            CustomerId::fromString(
                Auth::user()->currentTeam->plan->customer_id
            ),
        );

        return Inertia::location($url);
    }

}
