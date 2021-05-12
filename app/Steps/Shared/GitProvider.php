<?php

namespace App\Steps\Shared;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use Illuminate\Validation\Rule;
use App\Models\Pipeline\StepConfiguration;
use App\Enums\GitProvider as GitProviderType;

class GitProvider implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::GIT_PROVIDER;
    }

    public function component(): string
    {
        return 'GitProvider';
    }
    
    public function completed(): bool
    {
        return $this->flow->pipeline->hasConfig(
            StepType::fromString(
                StepType::GIT_PROVIDER,
            )
        );
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

    public function context(): array
    {
        return [];
    }

    public function createSteps(StepConfiguration $config): void
    {
        
    }

    public function cleanup(StepConfiguration $config): void
    {
        if ($config->details['value'] === GitProviderType::GITHUB) {
            return;
        }

        $type = StepType::fromString(StepType::GITHUB_AUTHENTICATION);

        if (!$this->flow->pipeline->hasConfig($type)) {
            return;
        }

        $githubAuthenticationConfig = $this->flow->pipeline->getConfig($type);

        $githubAuthenticationConfig?->delete();
    }

}
