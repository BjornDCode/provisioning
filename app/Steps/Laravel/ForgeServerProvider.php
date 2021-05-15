<?php

namespace App\Steps\Laravel;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use Illuminate\Validation\Rule;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\StepConfiguration;
use App\Clients\Forge\ApiClient as ForgeApiClient;

class ForgeServerProvider implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::FORGE_SERVER_PROVIDER;
    }

    public function component(): string
    {
        return 'Laravel/ForgeServerProvider';
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
                StepType::FORGE_SERVER_PROVIDER,
            ),
        );
    }

    public function validationRules(): array
    {
        return [
            'provider' => Rule::in($this->getValidServerProviders()),
            'credentials_id' => 'required',
        ];        
    }

    public function context(): array
    {
        return [
            'providers' => $this->getValidServerProviders(),
            'credentials' => $this->getCredentials(),
        ];
    }

    public function createSteps(StepConfiguration $config): void
    {}

    public function cleanup(StepConfiguration $config): void
    {}

    private function getValidServerProviders()
    {
        $client = app()->make(ForgeApiClient::class);

        $config = $this->flow->pipeline->getConfig(
            StepType::fromString(
                StepType::FORGE_AUTHENTICATION,
            ),
        );

        $account = Account::find($config->details['account_id']);

        return $client
            ->authenticate($account)
            ->getValidServerProviders();
    }

    private function getCredentials()
    {
        $client = app()->make(ForgeApiClient::class);

        $config = $this->flow->pipeline->getConfig(
            StepType::fromString(
                StepType::FORGE_AUTHENTICATION,
            ),
        );

        $account = Account::find($config->details['account_id']);

        return $client
            ->authenticate($account)
            ->listCredentials();
    }

}
