<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Pipeline\Account;
use App\Clients\Github\ApiClient;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RealGithubApiClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_authentication()
    {
        $this->expectException(InvalidCredentialsException::class);

        // Given
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

        // Cleanup
        $client
            ->authenticate($account)
            ->deleteRepository(
                env('GITHUB_ACCOUNT_NAME'),
                'Test'
            );
    }

    /** @test */
    public function it_can_delete_a_repository()
    {
        // Given
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

    /** @test */
    public function it_can_list_repositories()
    {
        // Given
        $client = $this->app->make(ApiClient::class);
        $account = Account::factory()->create([
            'identifier' => env('GITHUB_ACCOUNT_NAME'),
            'email' => env('GITHUB_ACCOUNT_EMAIL'),
            'user_id' => User::factory()->create()->id,
            'type' => 'github',
            'token' => env('GITHUB_ACCOUNT_TOKEN'),
        ]);
        // Make sure it shows up first in the list of return repositories
        $response = $client->authenticate($account)->createRepository('aaaaaa');

        // When
        $repositories = $client
            ->authenticate($account)
            ->listRepositories();

        // Then
        $this->assertEquals(
            env('GITHUB_ACCOUNT_NAME'),
            $repositories->first()->owner,
        );
        $this->assertEquals(
            'aaaaaa',
            $repositories->first()->name,
        );

        // Cleanup
        $client
            ->authenticate($account)
            ->deleteRepository(
                env('GITHUB_ACCOUNT_NAME'),
                'aaaaaa'
            );
    }


}
