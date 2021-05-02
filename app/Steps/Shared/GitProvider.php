<?php

namespace App\Steps\Shared;

use App\Steps\Step;
use App\Enums\GitProvider as GitProviderType;
use Illuminate\Validation\Rule;

class GitProvider implements Step
{

    public function type(): string
    {
        return 'git-provider';
    }

    public function component(): string
    {
        return 'GitProvider';
    }
    
    public function completed(): bool
    {
        return false;
    }

    public function validationRules(): array
    {
        return [
            'value' => [
                'required', 
                Rule::in(GitProviderType::all())
            ],
        ];        
    }

}
