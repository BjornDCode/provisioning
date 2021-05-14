<?php 

namespace App\Steps;

use App\Flows\Flow;
use App\Enums\StepType;
use App\Steps\Shared\GitProvider;
use App\Steps\Laravel\Environments;
use App\Steps\Laravel\HostingPrompt;
use App\Steps\Shared\ChooseRepository;
use App\Steps\Laravel\ForgeAuthentication;
use App\Steps\Laravel\ForgeServerProvider;
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
            StepType::CHOOSE_REPOSITORY => new ChooseRepository($flow),
            StepType::HOSTING_PROMPT => new HostingPrompt($flow),
            StepType::ENVIRONMENTS => new Environments($flow),
            StepType::FORGE_AUTHENTICATION => new ForgeAuthentication($flow),
            StepType::FORGE_SERVER_PROVIDER => new ForgeServerProvider($flow),
            default => throw new InvalidStepTypeException,
        };
    }

}
