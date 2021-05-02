<?php 

namespace App\Steps;

use App\Flows\Flow;
use App\Enums\StepType;
use App\Steps\Shared\GitProvider;
use App\Steps\Shared\GithubAuthentication;
use App\Exceptions\InvalidStepTypeException;
use App\Steps\Shared\NewOrExistingRepository;

class Factory
{

    public static function create(StepType $type, Flow $flow)
    {
        return match ($type->toString()) {
            StepType::GIT_PROVIDER => new GitProvider($flow),
            StepType::GITHUB_AUTHENTICATION => new GithubAuthentication($flow),
            StepType::NEW_OR_EXISTING_REPOSITORY => new NewOrExistingRepository($flow),
            default => throw new InvalidStepTypeException,
        };
    }

}
