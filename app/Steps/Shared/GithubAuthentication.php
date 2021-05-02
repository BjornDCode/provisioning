<?php

namespace App\Steps\Shared;

use App\Steps\Step;
use App\Enums\StepType;

class GithubAuthentication implements Step
{

    public function type(): string
    {
        return StepType::GITHUB_AUTHENTICATION;
    }

    public function component(): string
    {
        return 'GithubAuthentication';
    }
    
    public function completed(): bool
    {
        return false;
    }

    public function validationRules(): array
    {
        return [];        
    }

}
