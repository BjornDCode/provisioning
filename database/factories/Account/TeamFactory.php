<?php

namespace Database\Factories\Account;

use App\Models\Account\Team;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'name' => "{$user->name}'s Team",
            'owner_id' => $user->id,
        ];
    }
}
