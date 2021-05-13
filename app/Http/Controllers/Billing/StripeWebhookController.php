<?php

namespace App\Http\Controllers\Billing;

use App\Models\Billing\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripeWebhookController extends Controller
{
    
    public function webhook(Request $request)
    {
        return match($request->json('type')) {
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($request),
            default => null,
        };
    }

    public function handleSubscriptionUpdated($request)
    {
        $subscriptionId = $request->json('data.object.id');
        $planId = $request->json('data.object.items.data.0.price.id');
        $plan = Plan::where('subscription_id', $subscriptionId)->first();

        $plan->update([
            'plan_id' => $planId,
        ]);
    }

}
