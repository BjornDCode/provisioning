<?php

namespace App\Steps\Laravel;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\StepConfiguration;
use App\Clients\Forge\ApiClient as ForgeApiClient;

class ServerConfiguration implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::SERVER_CONFIGURATION;
    }

    public function component(): string
    {
        return 'Laravel/ServerConfiguration';
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
                StepType::SERVER_CONFIGURATION,
            )
        );
    }

    public function validationRules(): array
    {
        return [
            'region' => 'required|string',
            'size' => 'required|string',
        ];        
    }

    public function context(): array
    {
        $client = app()->make(ForgeApiClient::class);

        $authenticationConfig = $this->flow->pipeline->getConfig(
            StepType::fromString(
                StepType::FORGE_AUTHENTICATION,
            ),
        );

        $serverProviderConfig = $this->flow->pipeline->getConfig(
            StepType::fromString(
                StepType::FORGE_SERVER_PROVIDER,
            ),
        );

        $account = Account::find($authenticationConfig->details['account_id']);
        $provider = $serverProviderConfig->details['value'];

        return [
            'regions' => $client
                ->authenticate($account)
                ->listRegionsAndSizesForProvider($provider),
        ];
    }

    public function createSteps(StepConfiguration $config): void
    {
        $environmentsConfig = $this->flow->pipeline->getConfig(
            StepType::fromString(
                StepType::ENVIRONMENTS,
            )
        );

        if ($config->steps->count()) {
            return;
        }

        collect($environmentsConfig->details['value'])->each(function ($environment) use ($config) {
            $config->steps()->create([
                'title' => "Create {$environment} server",
            ]);
        });

    }

    public function cleanup(StepConfiguration $config): void
    {}

}
