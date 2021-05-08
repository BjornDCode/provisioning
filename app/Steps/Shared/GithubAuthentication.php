<?php

namespace App\Steps\Shared;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AccountResource;

class GithubAuthentication implements Step
{

    public function __construct(
        public Flow $flow
    ) {}

    public function type(): string
    {
        return StepType::GITHUB_AUTHENTICATION;
    }

    public function component(): string
    {
        return 'GithubAuthentication';
    }
    
    public function completed(): bool
    {
        return $this->flow->project->hasConfig(
            StepType::fromString(
                StepType::GITHUB_AUTHENTICATION,
            )
        );;
    }

    public function validationRules(): array
    {
        return [
            'account_id' => [
                'required',
                'exists:accounts,id',
                function ($attribute, $value, $fail) {
                    $account = Account::find($value);
                    
                    if (!Auth::user()->currentTeam->hasMember($account->user)) {
                        $fail('Invalid account.');
                    }
                }
            ]
        ];        
    }

    public function context(): array
    {
        return [
            'accounts' => AccountResource::collection(
                Auth::user()->accounts()->where('type', 'github')->get(),
            ),
        ];
    }

}
