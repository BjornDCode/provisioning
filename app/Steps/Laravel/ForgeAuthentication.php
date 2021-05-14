<?php

namespace App\Steps\Laravel;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use App\Models\Pipeline\Account;
use Illuminate\Support\Facades\Auth;
use App\Models\Pipeline\StepConfiguration;
use App\Http\Resources\Pipeline\AccountResource;

class ForgeAuthentication implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::FORGE_AUTHENTICATION;
    }

    public function component(): string
    {
        return 'Laravel/ForgeAuthentication';
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

        // Fix in next test
        return false;
    }

    public function validationRules(): array
    {
        return [
            'account_id' => [
                'required',
                'exists:accounts,id',
                function ($attribute, $value, $fail) {
                    $account = Account::find($value);
                    
                    if (is_null($account)){
                        return $fail('Account does not exist.');
                    }

                    if (!Auth::user()->currentTeam->hasMember($account->user)) {
                        return $fail('Invalid account.');
                    }
                }
            ]
        ];          
    }

    public function context(): array
    {
        return [
            'accounts' => AccountResource::collection(
                Auth::user()->accounts()->where('type', 'forge')->get(),
            ),
        ];
    }

    public function createSteps(StepConfiguration $config): void
    {}

    public function cleanup(StepConfiguration $config): void
    {}

}
