<?php

namespace Database\Factories\Pipeline;

use App\Enums\PipelineStatus;
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
            'title' => $this->faker->word,
            'status' => $this->faker->randomElement(PipelineStatus::all()),
            'config_id' => StepConfiguration::factory()->create()->id,
        ];
    }
}
