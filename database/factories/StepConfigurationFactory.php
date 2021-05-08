<?php

namespace Database\Factories;

use App\Models\Pipeline;
use App\Models\StepConfiguration;
use Illuminate\Database\Eloquent\Factories\Factory;

class StepConfigurationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StepConfiguration::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => 'git-provider',
            'pipeline_id' => Pipeline::factory()->create()->id,
            'details' => [],
        ];
    }
}
