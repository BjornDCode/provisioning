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

class ServerConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_server_configuration_step_page()
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
        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_SERVER_PROVIDER,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'provider' => 'ocean2',
            ],
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::SERVER_CONFIGURATION,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/Laravel/ServerConfiguration');
        });
    }

    /** @test */
    public function it_lists_regions_and_sizes()
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
        StepConfiguration::factory()->create([
            'type' => StepType::FORGE_SERVER_PROVIDER,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'provider' => 'ocean2',
            ],
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::SERVER_CONFIGURATION,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->has('regions', 1);
            $page->where('regions.0.id', 'ams2');
            $page->where('regions.0.sizes.0.size', 's-1vcpu-1gb');
        });
    }

    /** @test */
    public function it_can_save_the_configuration()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        StepConfiguration::factory()->create([
            'type' => StepType::ENVIRONMENTS,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => [
                    'Staging',
                ],
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::SERVER_CONFIGURATION,
                ]),
                [
                    'region' => 'ams2',
                    'size' => '01',
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::SERVER_CONFIGURATION)->first();
        $this->assertDatabaseHas('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'server-configuration',
        ]);
        $this->assertEquals('ams2', $config->details['region']);
        $this->assertEquals('01', $config->details['size']);
    }

    /** @test */
    public function region_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::SERVER_CONFIGURATION,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::SERVER_CONFIGURATION,
                ]),
                [
                    'size' => '01',
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'server-configuration',
            'details->size' => '01',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::SERVER_CONFIGURATION,
            ]),
        );
        $response->assertSessionHasErrors('region');
    }

    /** @test */
    public function size_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::SERVER_CONFIGURATION,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::SERVER_CONFIGURATION,
                ]),
                [
                    'region' => 'ams2',
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'server-configuration',
            'details->region' => 'ams2',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::SERVER_CONFIGURATION,
            ]),
        );
        $response->assertSessionHasErrors('size');
    }

    /** @test */
    public function it_creates_a_step_for_each_environment()
    {
        $this->app->bind(ForgeApiClient::class, ForgeFakeApiClient::class);
        
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $githubAccount = Account::factory()->create([
            'user_id' => $user->id,
            'type' => 'github',
        ]);
        $forgeAccount = Account::factory()->create([
            'user_id' => $user->id,
            'type' => 'forge',
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'details' => [
                'value' => 'new',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'github',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details' => [
                'account_id' => $githubAccount->id,
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::HOSTING_PROMPT,
            'details' => [
                'value' => true,
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::ENVIRONMENTS,
            'details' => [
                'value' => [
                    'Staging',
                    'Production',
                ],
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::FORGE_AUTHENTICATION,
            'details' => [
                'account_id' => $forgeAccount->id,
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::FORGE_SERVER_PROVIDER,
            'details' => [
                'value' => 'ocean2',
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::SERVER_CONFIGURATION,
                ]),
                [
                    'region' => 'ams2',
                    'size' => '01',
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::SERVER_CONFIGURATION)->first();
        $this->assertDatabaseHas('steps', [
            'config_id' => $config->id,
            'title' => 'Create Staging server',
            'status' => 'pending',
            'meta->environment' => 'Staging',
        ]);
        $this->assertDatabaseHas('steps', [
            'config_id' => $config->id,
            'title' => 'Create Production server',
            'status' => 'pending',
            'meta->environment' => 'Production',
        ]);
    }

}
