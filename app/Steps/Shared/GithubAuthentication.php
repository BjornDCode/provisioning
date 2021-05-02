<?php

namespace App\Steps\Shared;

use App\Steps\Step;

class GithubAuthentication implements Step
{

    public function type(): string
    {
        return 'github-account';
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
