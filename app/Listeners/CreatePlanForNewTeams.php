<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Payments\PaymentGateway;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePlanForNewTeams
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $customerId = $this->gateway->createCustomerForTeam($event->team);

        $event->team->plan()->create([
            'customer_id' => $customerId->toString(),
            'expires_at' => Carbon::now(),
        ]);
    }
}
