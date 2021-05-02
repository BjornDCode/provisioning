<?php

namespace App\Steps\Shared;

use App\Flows\Flow;
use App\Steps\Step;
use Illuminate\Validation\Rule;
use App\Enums\GitProvider as GitProviderType;

class GitProvider implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

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
        return !is_null($this->flow->project->gitProvider);
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
