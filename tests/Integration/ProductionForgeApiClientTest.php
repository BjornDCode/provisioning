<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Clients\Forge\ApiClient;
use App\Models\Pipeline\Account;
use Laravel\Forge\Resources\User;
use Illuminate\Support\Facades\Crypt;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductionForgeApiClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_authentication()
    {
        $this->expectException(InvalidCredentialsException::class);

        // Given
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
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ])->create();

        // When
        $user = $client->authenticate($account)->fetchUser();

        // Then
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Asbjørn Lindholm Hansen', $user->name);
    }

    /** @test */
    public function it_can_fetch_valid_server_providers()
    {
        // Given
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ])->create();

        // When
        $providers = $client->authenticate($account)->getValidServerProviders();

        // Then
        $this->assertEquals(1, count($providers));
        $this->assertEquals('ocean2', $providers[0]);
    }

    /** @test */
    public function it_can_list_regions_and_sizes()
    {
        // Given
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ])->create();

        // When
        $regions = $client->authenticate($account)->listRegionsAndSizesForProvider('ocean2');

        // Then
        $this->assertEquals('ams2', $regions[0]['id']);
    }

    /** @test */
    public function it_can_create_a_server()
    {
        // Given
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ])->create();

        // When
        $server = $client->authenticate($account)->createServer([
            'name' => 'test-provisioning',
            'type' => 'app',
            'provider' => 'ocean2',
            'region' => 'nyc3',
            'size' => '01',
            'php_version' => 'php80',
            'credential_id' => env('FORGE_CREDENTIALS_ID'),
        ]);

        // Then
        $this->assertEquals('test-provisioning', $server->name);

        // Cleanup
        $client->authenticate($account)->deleteServer($server->id);
    }

    /** @test */
    public function it_can_delete_a_server()
    {
        // Given
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ])->create();
        $server = $client->authenticate($account)->createServer([
            'name' => 'test-provisioning',
            'type' => 'app',
            'provider' => 'ocean2',
            'region' => 'nyc3',
            'size' => '01',
            'php_version' => 'php80',
            'credential_id' => env('FORGE_CREDENTIALS_ID'),
        ]);

        // When
        $response = $client->authenticate($account)->deleteServer($server->id);

        // Then
        $this->assertNull($response);
    }


    /** @test */
    public function it_can_fetch_a_server()
    {
        // Given
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ])->create();
        $server = $client->authenticate($account)->createServer([
            'name' => 'test-provisioning',
            'type' => 'app',
            'provider' => 'ocean2',
            'region' => 'nyc3',
            'size' => '01',
            'php_version' => 'php80',
            'credential_id' => env('FORGE_CREDENTIALS_ID'),
        ]);

        // When
        $server = $client->authenticate($account)->fetchServer($server->id);

        // Then
        $this->assertEquals('test-provisioning', $server->name);

        // Cleanup
        $client->authenticate($account)->deleteServer($server->id);
    }

    /** @test */
    public function it_can_list_credentials_for_provider()
    {
        // Given
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory([
            'type' => 'forge',
            'token' => Crypt::encryptString(env('FORGE_API_KEY')),
        ])->create();

        // When
        $credentials = $client->authenticate($account)->listCredentials();

        // Then
        $this->assertEquals(env('FORGE_CREDENTIALS_ID'), $credentials[0]['id']);
    }

}
