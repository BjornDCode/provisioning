<?php

namespace App\Clients\Github;

use App\Models\Pipeline\Account;
use App\Clients\Github\ApiClient;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Exceptions\InvalidCredentialsException;

class ProductionApiClient implements ApiClient
{
    protected ?Account $account = null;

    public function authenticate(Account $account): ApiClient
    {
        $this->account = $account;

        return $this;
    }

    public function createRepository(string $name): Response
    {
        if (is_null($this->account)) {
            throw new InvalidCredentialsException;
        }

        $response = Http::withHeaders([
            'Authorization' => 'token ' . $this->account->token,
        ])->post('https://api.github.com/user/repos', [
            'name' => $name,
            'private' => true,
        ]);

        return $response;
    }

    public function deleteRepository(string $owner, string $name): Response
    {
        if (is_null($this->account)) {
            throw new InvalidCredentialsException;
        }

        $response = Http::withHeaders([
            'Authorization' => 'token ' . $this->account->token,
        ])->delete("https://api.github.com/repos/{$owner}/{$name}");

        return $response;
    }

    public function listRepositories(): Collection
    {
        if (is_null($this->account)) {
            throw new InvalidCredentialsException;
        }

        $response = Http::withHeaders([
            'Authorization' => 'token ' . $this->account->token,
        ])->get("https://api.github.com/user/repos");

        $repositories = $response->collect()->map(function ($repository) {
            return (object) [
                'owner' => $repository['owner']['login'],
                'name' => $repository['name'],
            ];
        });

        return $repositories;
    }

}
