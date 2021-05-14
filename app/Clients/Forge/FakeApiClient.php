<?php

namespace App\Clients\Forge;

use Laravel\Forge\Forge;
use App\Models\Pipeline\Account;
use Laravel\Forge\Resources\User;
use App\Exceptions\InvalidCredentialsException;

class FakeApiClient implements ApiClient
{
    public ?Forge $instance = null;

    public function authenticate(Account $account): ApiClient
    {
        $this->instance = new Forge($account->token);

        return $this;
    }

    public function fetchUser(): User
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        return new User([
            'id' => 1,
            'name' => 'Bjorn Lindholm',
            'email' => 'bjornlindholmhansen@gmail.com',
            'cardLastFour' => '9999',
            'connectedToGithub' => true,
            'connectedToGitlab' => false,
            'connectedToBitbucket' => false,
            'connectedToBitbucketTwo' => false,
            'connectedToDigitalocean' => true,
            'connectedToLinode' => true,
            'connectedToVultr' => false,
            'connectedToAws' => false,
            'readyForBilling' => true,
            'stripeIsActive' => true,
            'stripePlan' => 'plan_1234',
            'subscribed' => 1,
            'canCreateServers' => true,
        ]);
    }

    public function getValidServerProviders(): array
    {
        return [
            'ocean2',
            'linode',
        ];
    }

}
