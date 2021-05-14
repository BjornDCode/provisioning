<?php

namespace App\Clients\Forge;

use Laravel\Forge\Forge;
use App\Models\Pipeline\Account;
use Laravel\Forge\Resources\User;
use Illuminate\Support\Facades\Crypt;
use App\Exceptions\InvalidCredentialsException;

class ProductionApiClient implements ApiClient
{
    public ?Forge $instance = null;

    public function authenticate(Account $account): ApiClient
    {
        $this->instance = new Forge(
            Crypt::decryptString($account->token),
        );

        return $this;
    }

    public function fetchUser(): User
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        return $this->instance->user();
    }
    
}
