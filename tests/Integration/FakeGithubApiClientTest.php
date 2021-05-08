<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\Account;
use App\Models\Auth\User;
use App\Clients\Github\ApiClient;
use App\Clients\Github\TestApiClient;
use App\Exceptions\InvalidCredentialsException;

class FakeGithubApiClientTest extends TestCase
{

    /** @test */
    public function it_requires_authentication()
    {
        $this->expectException(InvalidCredentialsException::class);

        // Given
        $this->app->bind(ApiClient::class, TestApiClient::class);
        $client = $this->app->make(ApiClient::class);

        // When
        $client->createRepository('Test');

        // Then
        // See expected exception
    }

    /** @test */
    public function it_can_create_a_repository()
    {
        // Given
        $this->app->bind(ApiClient::class, TestApiClient::class);
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory()->create([
            'identifier' => env('GITHUB_ACCOUNT_NAME'),
            'email' => env('GITHUB_ACCOUNT_EMAIL'),
            'user_id' => User::factory()->create()->id,
            'type' => 'github',
            'token' => env('GITHUB_ACCOUNT_TOKEN'),
        ]);

        // When
        $response = $client->authenticate($account)->createRepository('Test');

        // Then
        $this->assertEquals(
            "https://github.com/" . env('GITHUB_ACCOUNT_NAME') . "/Test.git",
            $response->collect()->get('clone_url'),
        );
    }

    /** @test */
    public function it_can_delete_a_repository()
    {
        // Given
        $this->app->bind(ApiClient::class, TestApiClient::class);
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory()->create([
            'identifier' => env('GITHUB_ACCOUNT_NAME'),
            'email' => env('GITHUB_ACCOUNT_EMAIL'),
            'user_id' => User::factory()->create()->id,
            'type' => 'github',
            'token' => env('GITHUB_ACCOUNT_TOKEN'),
        ]);
        $response = $client->authenticate($account)->createRepository('Test');

        // When
        $response = $client
            ->authenticate($account)
            ->deleteRepository(
                env('GITHUB_ACCOUNT_NAME'),
                'Test'
            );

        // Then
        $this->assertTrue(
            $response->successful()
        );
    }
}
