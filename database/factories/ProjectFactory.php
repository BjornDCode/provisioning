<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Account\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

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
