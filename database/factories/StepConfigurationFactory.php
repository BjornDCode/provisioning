<?php

namespace Database\Factories;

use App\Models\Project;
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
            'project_id' => Project::factory()->create()->id,
            'details' => [],
        ];
    }
}
