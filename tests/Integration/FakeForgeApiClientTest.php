<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Clients\Forge\ApiClient;
use App\Models\Pipeline\Account;
use Laravel\Forge\Resources\User;
use App\Clients\Forge\FakeApiClient;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FakeForgeApiClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_authentication()
    {
        $this->expectException(InvalidCredentialsException::class);

        // Given
        $this->app->bind(ApiClient::class, FakeApiClient::class);
        $client = $this->app->make(ApiClient::class);

        // When
        $client->fetchUser();

        // Then
        // See expected exception
    }

    /** @test */
    public function it_can_fetch_a_user()
    {
        // Given
        $this->app->bind(ApiClient::class, FakeApiClient::class);
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
        ])->create();

        // When
        $user = $client->authenticate($account)->fetchUser();

        // Then
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Bjorn Lindholm', $user->name);
    }

    /** @test */
    public function it_can_fetch_valid_server_providers()
    {
        // Given
        $this->app->bind(ApiClient::class, FakeApiClient::class);
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
        ])->create();

        // When
        $providers = $client->authenticate($account)->getValidServerProviders();

        // Then
        $this->assertEquals(2, count($providers));
        $this->assertEquals('ocean2', $providers[0]);
    }

    /** @test */
    public function it_can_list_regions_and_sizes()
    {
        // Given
        $this->app->bind(ApiClient::class, FakeApiClient::class);
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
        ])->create();

        // When
        $regions = $client->authenticate($account)->listRegionsAndSizesForProvider('ocean2');

        // Then
        $this->assertEquals('ams2', $regions[0]['id']);
    }

}
