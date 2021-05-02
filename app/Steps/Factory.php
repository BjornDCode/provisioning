<?php 

namespace App\Steps;

use App\Flows\Flow;
use App\Enums\StepType;
use App\Steps\Shared\GitProvider;
use App\Steps\Shared\GithubAuthentication;
use App\Exceptions\InvalidStepTypeException;

class Factory
{

    public static function create(StepType $type, Flow $flow)
    {
        return match ($type->toString()) {
            StepType::GIT_PROVIDER => new GitProvider($flow),
            StepType::GITHUB_AUTHENTICATION => new GithubAuthentication($flow),
            default => throw new InvalidStepTypeException,
        };
    }

}
