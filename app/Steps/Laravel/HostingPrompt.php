<?php

namespace App\Steps\Laravel;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use App\Models\Pipeline\StepConfiguration;

class HostingPrompt implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::HOSTING_PROMPT;
    }

    public function component(): string
    {
        return 'Laravel/HostingPrompt';
    }
    
    public function completed(): bool
    {
        return false;
    }

    public function validationRules(): array
    {
        return [
            'value' => 'required|boolean',
        ];        
    }

    public function context(): array
    {
        return [];
    }

    public function createSteps(StepConfiguration $config): void
    {}

    public function cleanup(StepConfiguration $config): void
    {}

}
