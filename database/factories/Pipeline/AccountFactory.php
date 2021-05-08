<?php

namespace Database\Factories\Pipeline;

use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Models\Pipeline\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'identifier' => 'BjornDCode',
            'email' => $this->faker->email,
            'user_id' => User::factory()->create()->id,
            'type' => 'github',
            'token' => Str::random(20),
        ];
    }
}
