<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use Inertia\Testing\Assert;
use App\Enums\PipelineStatus;
use App\Models\Pipeline\Step;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GithubAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_git_provider_step_page()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::GITHUB_AUTHENTICATION,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/GithubAuthentication');
        });
    }

    /** @test */
    public function it_renders_existing_accounts()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'identifier' => 'BjornDCode',
            'user_id' => $user->id,
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::GITHUB_AUTHENTICATION,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->has('accounts', 1);
            $page->where('accounts.0.identifier', 'BjornDCode');
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
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $this->assertDatabaseHas('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details->account_id' => $account->id,
        ]);
    }

    /** @test */
    public function it_must_be_an_existing_git_account()
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
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => 'fake_id', 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details->account_id' => 'fake_id',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::GITHUB_AUTHENTICATION,
            ]),
        );
        $response->assertSessionHasErrors('account_id');
    }

    /** @test */
    public function the_account_must_belong_to_a_user_in_the_team()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create();

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details->account_id' => $account->id,
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::GITHUB_AUTHENTICATION,
            ]),
        );
        $response->assertSessionHasErrors('account_id');
    }

    /** @test */
    public function it_creates_a_step_if_the_flow_should_create_a_new_repository()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
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

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );


        // Then
        $config = StepConfiguration::where('type', StepType::GITHUB_AUTHENTICATION)->first();
        $this->assertDatabaseHas('steps', [
            'config_id' => $config->id,
            'title' => 'Create repository',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_does_not_recreate_a_step_when_updating_the_config()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
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
        $config = StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
            'config_id' => $config->id,
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $this->assertDatabaseCount('steps', 1);
        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
        ]);
    }

    /** @test */
    public function it_does_not_create_a_step_if_the_flow_uses_an_existing_repository()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'details' => [
                'value' => 'existing',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'github',
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );


        // Then
        $config = StepConfiguration::where('type', StepType::GITHUB_AUTHENTICATION)->first();
        $this->assertDatabaseMissing('steps', [
            'config_id' => $config->id,
        ]);
    }

}
