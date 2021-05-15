<?php

namespace App\Clients\Forge;

use Laravel\Forge\Forge;
use App\Models\Pipeline\Account;
use Laravel\Forge\Resources\User;
use Laravel\Forge\Resources\Server;
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

    public function listRegionsAndSizesForProvider(string $provider): array
    {
        return [
            [
                'id' => 'ams2',
                'name' => 'Amsterdam 2',
                'sizes' => [
                    [
                        'id' => '01',
                        'size' => 's-1vcpu-1gb',
                        'name' => "1GB RAM - 1 CPU Core - 25GB SSD",
                    ],
                ],
            ]
        ];
    }

    public function createServer(array $data): Server
    {
        return new Server($data);
    }

    public function deleteServer($id)
    {
        return null;
    }

    public function fetchServer($id): Server
    {
        return new Server([
            'name' => 'test-provisioning',
            'type' => 'app',
            'provider' => 'ocean2',
            'region' => 'nyc3',
            'size' => '01',
            'php_version' => 'php80',
            'credential_id' => 1,
        ]);
    }

    public function listCredentials(string $provider): array
    {
        return [
            [
                'id' => 1,
                'type' => 'ocean2',
                'name' => 'Person',
            ],
        ];
    }

}
