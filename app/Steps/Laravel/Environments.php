<?php

namespace App\Steps\Laravel;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use App\Models\Pipeline\StepConfiguration;

class Environments implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::ENVIRONMENTS;
    }

    public function component(): string
    {
        return 'Laravel/Environments';
    }
    
    public function completed(): bool
    {
        $hostingConfig = $this->flow->pipeline->getConfig(
            StepType::fromString(
                StepType::HOSTING_PROMPT,
            )
        );

        if (is_null($hostingConfig)) {
            return false;
        }

        // If no hosting
        if ($hostingConfig->details['value'] === false) {
            return true;
        }

        return $this->flow->pipeline->hasConfig(
            StepType::fromString(
                StepType::ENVIRONMENTS,
            )
        );
    }

    public function validationRules(): array
    {
        return [
            'value' => 'required|array',
            'value.*' => 'distinct',
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
