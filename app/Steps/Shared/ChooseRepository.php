<?php

namespace App\Steps\Shared;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use Illuminate\Validation\Rule;
use App\Clients\Github\ApiClient as GithubApiClient;
use App\Models\Pipeline\StepConfiguration;
use App\Enums\GitProvider as GitProviderType;

class ChooseRepository implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::CHOOSE_REPOSITORY;
    }

    public function component(): string
    {
        return 'ChooseRepository';
    }
    
    public function completed(): bool
    {
        $newOrExistingConfig = $this->flow->pipeline->getConfig(
            StepType::fromString(StepType::NEW_OR_EXISTING_REPOSITORY)
        );

        // They shouldn't choose a repository if they are gonna create a new one.
        if ($newOrExistingConfig->details['value'] === 'new') {
            return true;
        }

        return $this->flow->pipeline->hasConfig(
            StepType::fromString(
                StepType::CHOOSE_REPOSITORY,
            )
        );
    }

    public function validationRules(): array
    {
        return [];        
    }

    public function context(): array
    {
        $client = app()->make(GithubApiClient::class);

        $repositories = $client
            ->authenticate($this->flow->pipeline->gitAccount)
            ->listRepositories()
            ->toArray();

        return [
            'repositories' => $repositories,
        ];
    }

    public function createSteps(StepConfiguration $config): void
    {
        
    }

    public function cleanup(StepConfiguration $config): void
    {

    }

}
