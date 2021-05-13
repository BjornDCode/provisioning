<?php

namespace App\Payments;

use Stripe\Stripe;
use App\CustomerId;
use Stripe\Customer;
use App\SubscriptionId;
use Stripe\Subscription;
use App\Models\Account\Team;
use App\Models\Billing\Plan;
use App\Payments\PaymentGateway;
use Stripe\BillingPortal\Session;
use Illuminate\Support\Facades\Config;

class StripePaymentGateway implements PaymentGateway
{

    public function __construct()
    {
        Stripe::setApiKey(
            Config::get('services.stripe.secret'),
        );
    }

    public function createCustomerForTeam(Team $team): CustomerId
    {
         $customer = Customer::create([
            'email' => $team->owner->email,
            'name' => $team->name,
        ]);

        return CustomerId::fromString($customer->id);
    }

    public function createBillingSessionForCustomer(CustomerId $id): string
    {
        $session = Session::create([
            'customer' => $id->toString(),
            'return_url' => route('pipelines.index')
        ]);

        return $session->url;
    }

    public function subscribeCustomerToFreePlan(CustomerId $id): SubscriptionId
    {
        $subscription = Subscription::create([
            'customer' => $id->toString(),
            'items' => [
                [
                    'price' => Config::get('services.stripe.free_price_id'),
                ],
            ],
        ]);

        $plan = Plan::where('customer_id', $id->toString())->first();
        $plan->update([
            'subscription_id' => $subscription->id,
        ]);

        return SubscriptionId::fromString($subscription->id);
    }
    
}
