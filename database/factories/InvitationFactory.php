<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory()->create()->id,
            'email' => $this->faker->unique()->safeEmail,
            'token' => Str::random(32),
        ];
    }
}
