<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Auth\User;
use App\Models\Account\Team;
use App\Models\Billing\Plan;
use Illuminate\Database\Seeder;
use App\Models\Pipeline\Account;
use App\Payments\PaymentGateway;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        $team = Team::factory()->create([
            'owner_id' => $user->id,
        ]);

        $membershipteam = Team::factory()->create();

        $membershipteam->join($user);

        $user->current_team_id = $team->id;
        $user->save();

        $invitee = User::factory()->create();
        $team->invite($invitee->email);

        $member = User::factory()->create();
        $team->join($member);

        $paymentGateway = app()->make(PaymentGateway::class);

        $customerId = $paymentGateway->createCustomerForTeam($team);

        $plan = Plan::factory()->create([
            'team_id' => $team->id,
            'customer_id' => $customerId->toString(),
            'subscription_id' => null,
            'plan_id' => null,
        ]);

        $paymentGateway->subscribeCustomerToFreePlan($customerId);

        Account::factory()->create([
            'identifier' => 'Real account',
            'email' => $user->email,
            'user_id' => $user->id,
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ]);

    }
}
