<?php

namespace Database\Factories\Billing;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Account\Team;
use App\Models\Billing\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory()->create()->id,
            'customer_id' => Str::random(10),
            'subscription_id' => Str::random(10),
            'expires_at' => Carbon::now(),
        ];
    }
}
