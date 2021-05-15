<?php

namespace App\Clients\Forge;

use App\Models\Pipeline\Account;
use Laravel\Forge\Resources\User;
use Illuminate\Support\Collection;
use Laravel\Forge\Resources\Server;
use Illuminate\Http\Client\Response;

interface ApiClient
{

    public function authenticate(Account $account): ApiClient;

    public function fetchUser(): User;

    public function getValidServerProviders(): array;

    public function listRegionsAndSizesForProvider(string $provider): array;

    public function createServer(array $data): Server;

    public function deleteServer($id);

    public function fetchServer($id): Server;

    public function listCredentials(): array;

}
