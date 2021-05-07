<?php

namespace App\Steps\Shared;

use App\Flows\Flow;
use App\Steps\Step;
use App\Enums\StepType;
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
        return false;
    }

    public function validationRules(): array
    {
        return [];        
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
