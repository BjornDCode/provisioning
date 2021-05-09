<?php

namespace App\Steps\Shared;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use Illuminate\Validation\Rule;
use App\Models\Pipeline\StepConfiguration;
use App\Enums\GitProvider as GitProviderType;

class NewOrExistingRepository implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::NEW_OR_EXISTING_REPOSITORY;
    }

    public function component(): string
    {
        return 'NewOrExistingRepository';
    }
    
    public function completed(): bool
    {
        return $this->flow->pipeline->hasConfig(
            StepType::fromString(
                StepType::NEW_OR_EXISTING_REPOSITORY,
            )
        );
    }

    public function validationRules(): array
    {
        return [
            'value' => [
                'required',
                Rule::in(['new', 'existing']),
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
        if ($config->details['value'] === 'existing') {
            $step = $this->flow->pipeline->steps()->where('title', 'Create repository')->first();
            $step?->delete(); 
        }
    }

}
