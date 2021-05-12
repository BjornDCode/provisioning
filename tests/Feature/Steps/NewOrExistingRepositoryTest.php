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

class NewOrExistingRepositoryTest extends TestCase
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
                'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/NewOrExistingRepository');
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

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
                [
                    'value' => 'new', 
                ]
            );

        // Then
        $this->assertDatabaseHas('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'new-or-existing-repository',
            'details->value' => 'new',
        ]);
    }

    /** @test */
    public function it_must_be_a_valid_answer()
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
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
                [
                    'value' => 'invalid', 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'new-or-existing-repository',
            'details->value' => 'invalid',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
            ]),
        );
        $response->assertSessionHasErrors('value');
    }

    /** @test */
    public function it_does_not_create_a_runnable_step()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
                [
                    'value' => 'new', 
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::NEW_OR_EXISTING_REPOSITORY)->first();
        $this->assertDatabaseMissing('steps', [
            'config_id' => $config->id,
        ]);
    }

    /** @test */
    public function it_removes_a_create_repository_step_if_the_configuration_changes_to_use_existing_repo()
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
        $githubAuthenticationConfig = StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
            'config_id' => $githubAuthenticationConfig,
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
                [
                    'value' => 'existing',
                ]
            );


        // Then
        $this->assertDatabaseMissing('steps', [
            'id' => $step->id,
        ]);
    }

}
