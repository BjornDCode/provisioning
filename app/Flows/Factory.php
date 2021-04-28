<?php 

namespace App\Flows;

use App\Enums\ProjectType;
use App\Flows\Laravel\Flow as LaravelFlow;
use App\Exceptions\InvalidProjectTypeException;

class Factory
{

    public static function create(ProjectType $type)
    {
        return match ($type->toString()) {
            ProjectType::LARAVEL => new LaravelFlow(),
            default => throw new InvalidProjectTypeException,
        };
    }

}
