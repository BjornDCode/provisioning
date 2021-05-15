<?php

namespace App\Clients\Forge;

use Laravel\Forge\Forge;
use App\Models\Pipeline\Account;
use Laravel\Forge\Resources\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Exceptions\InvalidCredentialsException;

class ProductionApiClient implements ApiClient
{
    public ?Account $account = null;
    public ?Forge $instance = null;

    public function authenticate(Account $account): ApiClient
    {
        $this->account = $account;
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

    public function getValidServerProviders(): array
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        $user = $this->fetchUser();

        $providers = [];

        if ($user->connectedToDigitalocean) {
            $providers[] = 'ocean2';
        }

        if ($user->connectedToLinode) {
            $providers[] = 'linode';
        }

        if ($user->connectedToVultr) {
            $providers[] = 'vultr';
        }

        if ($user->connectedToAws) {
            $providers[] = 'aws';
        }

        return $providers;
    }

    public function listRegionsAndSizesForProvider(string $provider): array
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . Crypt::decryptString($this->account->token),
        ])->get('https://forge.laravel.com/api/v1/regions');

        return collect($response->collect()->get('regions'))->get($provider);
    }

    public function createServer(array $data)
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        return $this->instance->createServer($data);
    }

    public function deleteServer($id)
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        return $this->instance->deleteServer($id);
    }

    public function fetchServer($id)
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        return $this->instance->server($id);
    }

    public function listCredentials(string $provider): array
    {
        if (is_null($this->instance)) {
            throw new InvalidCredentialsException;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . Crypt::decryptString($this->account->token),
        ])->get('https://forge.laravel.com/api/v1/credentials');

        return collect($response->collect()->get('credentials'))->filter(function ($credential) use ($provider) {
            return $credential['type'] === $provider;
        })->toArray();
    }
    
}
