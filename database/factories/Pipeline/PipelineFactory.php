<?php

namespace Database\Factories\Pipeline;

use App\Models\Account\Team;
use App\Models\Pipeline\Pipeline;
use Illuminate\Database\Eloquent\Factories\Factory;

class PipelineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pipeline::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Hel',
            'type' => 'laravel',
            'team_id' => Team::factory()
        ];
    }
}
