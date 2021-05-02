<?php 

namespace App\Flows;

use App\Models\Project;
use App\Enums\ProjectType;
use App\Flows\Laravel\Flow as LaravelFlow;
use App\Exceptions\InvalidProjectTypeException;

class Factory
{

    public static function create(Project $project)
    {
        return match ($project->type) {
            ProjectType::LARAVEL => new LaravelFlow($project),
            default => throw new InvalidProjectTypeException,
        };
    }

}
