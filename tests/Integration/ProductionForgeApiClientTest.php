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

}
