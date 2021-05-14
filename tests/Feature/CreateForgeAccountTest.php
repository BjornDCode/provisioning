<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pipeline\Account;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateForgeAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_cannot_create_forge_accounts()
    {
        $this->withExceptionHandling();

        // When
        $response = $this->post(
            route('accounts.forge.store'),
            [
                'name' => 'Primary',
                'key' => 'api_key_123',
            ]
        );

        // Then
        $response->assertRedirect('login');
    }

    /** @test */
    public function it_can_create_a_forge_account()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(
                route('pipelines.index')
            )
            ->post(
                route('accounts.forge.store'),
                [
                    'name' => 'Primary',
                    'key' => 'api_key_123',
                ]
            );

        // Then
        $this->assertDatabaseHas('accounts', [
            'type' => 'forge',
            'identifier' => 'Primary',
            'user_id' => $user->id,
        ]);
        $response->assertRedirect(
            route('pipelines.index'),
        );
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(
                route('pipelines.index')
            )
            ->post(
                route('accounts.forge.store'),
                [
                    'key' => 'api_key_123',
                ]
            );

        // Then
        $response->assertRedirect(
            route('pipelines.index')
        );
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function api_key_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(
                route('pipelines.index')
            )
            ->post(
                route('accounts.forge.store'),
                [
                    'name' => 'Bjorn Lindholm',
                ]
            );

        // Then
        $response->assertRedirect(
            route('pipelines.index')
        );
        $response->assertSessionHasErrors('key');

    }

    /** @test */
    public function it_encrypts_the_api_key()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(
                route('pipelines.index')
            )
            ->post(
                route('accounts.forge.store'),
                [
                    'name' => 'Primary',
                    'key' => 'api_key_123',
                ]
            );


        // Then
        $account = Account::where('type', 'forge')->first();
        $this->assertEquals(
            'api_key_123', 
            Crypt::decryptString($account->token)
        );
    }

}
