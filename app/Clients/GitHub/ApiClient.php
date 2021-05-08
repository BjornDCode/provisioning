<?php

namespace App\Clients\Github;

use App\Models\Account;
use Illuminate\Http\Client\Response;

interface ApiClient
{

    public function authenticate(Account $account): ApiClient;

    public function createRepository(string $name): Response;

    public function deleteRepository(string $owner, string $name): Response;

}
