<?php

namespace App\Clients\Github;

use App\Models\Pipeline\Account;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;

interface ApiClient
{

    public function authenticate(Account $account): ApiClient;

    public function createRepository(string $name): Response;

    public function deleteRepository(string $owner, string $name): Response;

    public function listRepositories(string $owner): Collection;

}
