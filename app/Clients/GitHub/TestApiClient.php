<?php

namespace App\Clients\Github;

use App\Models\Account;
use App\Clients\Github\ApiClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Clients\Github\ProductionApiClient;
use App\Exceptions\InvalidCredentialsException;

class TestApiClient implements ApiClient
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

        Http::fake([
            'https://api.github.com/user/repos' => Http::response([
                'clone_url' => "https://github.com/" . env('GITHUB_ACCOUNT_NAME') . "/" . $name . ".git"
            ], 200)
        ]);

        $client = new ProductionApiClient();
        $response = $client
            ->authenticate($this->account)
            ->createRepository($name);

        return $response;
    }

    public function deleteRepository(string $owner, string $name): Response
    {
        if (is_null($this->account)) {
            throw new InvalidCredentialsException;
        }

        Http::fake([
            'https://api.github.com/repos/*' => Http::response([], 204)
        ]);

        $client = new ProductionApiClient();
        $response = $client
            ->authenticate($this->account)
            ->deleteRepository($owner, $name);

        return $response;
    }

}
