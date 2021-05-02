<?php 

namespace App\Steps;

use App\Enums\StepType;
use App\Steps\Shared\GitProvider;
use App\Steps\Shared\GithubAuthentication;
use App\Exceptions\InvalidStepTypeException;

class Factory
{

    public static function create(StepType $type)
    {
        return match ($type->toString()) {
            StepType::GIT_PROVIDER => new GitProvider,
            StepType::GITHUB_AUTHENTICATION => new GithubAuthentication,
            default => throw new InvalidStepTypeException,
        };
    }

}