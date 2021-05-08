<?php 

namespace App\Flows;

use App\Enums\PipelineType;
use App\Models\Pipeline\Pipeline;
use App\Flows\Laravel\Flow as LaravelFlow;
use App\Exceptions\InvalidPipelineTypeException;

class Factory
{

    public static function create(Pipeline $pipeline)
    {
        return match ($pipeline->type) {
            PipelineType::LARAVEL => new LaravelFlow($pipeline),
            default => throw new InvalidPipelineTypeException,
        };
    }

}
