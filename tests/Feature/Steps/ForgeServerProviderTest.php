<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use Inertia\Testing\Assert;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use App\Clients\Forge\ApiClient as ForgeApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Clients\Forge\FakeApiClient as ForgeFakeApiClient;

class ForgeServerProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_forge_server_provider_step_page()
    {
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_SERVER_PROVIDER,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/Laravel/ForgeServerProvider');
        });
    }


    /** @test */
    public function it_lists_valid_server_providers()
    {
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);
        
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_SERVER_PROVIDER,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->has('providers', 2);
            $page->where('providers.0', 'ocean2');
        });
    }

    /** @test */
    public function it_lists_credentials()
    {
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);
        
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_SERVER_PROVIDER,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->has('credentials', 1);
            $page->where('credentials.0.type', 'ocean2');
        });
    }


    /** @test */
    public function it_can_save_the_configuration()
    {
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_SERVER_PROVIDER,
                ]),
                [
                    'provider' => 'ocean2',
                    'credentials_id' => 1,
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::FORGE_SERVER_PROVIDER)->first();
        $this->assertDatabaseHas('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'forge-server-provider',
            'details->provider' => 'ocean2',
            'details->credentials_id' => 1,
        ]);
    }

    /** @test */
    public function it_must_be_a_valid_server_provider()
    {
        $this->withExceptionHandling();
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_SERVER_PROVIDER,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_SERVER_PROVIDER,
                ]),
                [
                    'provider' => 'invalid', 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'forge-server-provider',
            'details->provider' => 'invalid',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_SERVER_PROVIDER,
            ]),
        );
        $response->assertSessionHasErrors('provider');
    }

    /** @test */
    public function it_requires_credentials()
    {
        $this->withExceptionHandling();
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_SERVER_PROVIDER,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_SERVER_PROVIDER,
                ]),
                [
                    'provider' => 'ocean2', 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'forge-server-provider',
            'details->credentials_id' => 'invalid',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_SERVER_PROVIDER,
            ]),
        );
        $response->assertSessionHasErrors('credentials_id');
    }

    /** @test */
    public function it_does_not_create_a_runnable_step()
    {
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_SERVER_PROVIDER,
                ]),
                [
                    'provider' => 'ocean2', 
                    'credentials_id' => 1, 
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::FORGE_SERVER_PROVIDER)->first();
        $this->assertDatabaseMissing('steps', [
            'config_id' => $config->id,
        ]);
    }

}
