<?php

namespace Database\Factories\Pipeline;

use App\Models\Pipeline\Step;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Database\Eloquent\Factories\Factory;

class StepFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Step::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'config_id' => StepConfiguration::factory()->create()->id,
            'status' => 'pending',
        ];
    }
}
