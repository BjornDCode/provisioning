<?php

namespace Tests\Feature;

use Exception;
use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use App\Enums\PipelineStatus;
use App\Jobs\ExecutePipeline;
use App\Models\Pipeline\Step;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Events\PipelineStepFailed;
use App\Events\PipelineStepRunning;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Events\PipelineStepSuccessful;
use Illuminate\Queue\Events\JobFailed;
use App\Jobs\ExecuteCreateRepositoryStep;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExecutePipelinesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_authenticated_user_cannot_execute_pipelines()
    {
        $this->withExceptionHandling();

        // Given
        $pipeline = Pipeline::factory()->create();
        $account = Account::factory()->create([
            'type' => GitProvider::GITHUB,
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'type' => StepType::GITHUB_AUTHENTICATION,
                'pipeline_id' => $pipeline->id,
                'details' => [
                    'account_id' => $account->id,
                ],
            ]),
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => 'new',
            ],
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::GIT_PROVIDER,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => GitProvider::GITHUB,
            ],
        ]);

        // When
        $response = $this->post(
            route('pipelines.execute', [ 'pipeline' => $pipeline->id, ]),
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_cannot_execute_another_teams_pipeline()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create();
        $account = Account::factory()->create([
            'type' => GitProvider::GITHUB,
            'user_id' => $user->id,
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'type' => StepType::GITHUB_AUTHENTICATION,
                'pipeline_id' => $pipeline->id,
                'details' => [
                    'account_id' => $account->id,
                ],
            ]),
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => 'new',
            ],
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::GIT_PROVIDER,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => GitProvider::GITHUB,
            ],
        ]);

        // When
        $response = $this->post(
            route('pipelines.execute', [ 'pipeline' => $pipeline->id, ]),
        );

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_execute_their_own_teams_pipelines()
    {
        Queue::fake();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => GitProvider::GITHUB,
            'user_id' => $user->id,
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'type' => StepType::GITHUB_AUTHENTICATION,
                'pipeline_id' => $pipeline->id,
                'details' => [
                    'account_id' => $account->id,
                ],
            ]),
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => 'new',
            ],
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::GIT_PROVIDER,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => GitProvider::GITHUB,
            ],
        ]);

        // When
        $response = $this->post(
            route('pipelines.execute', [ 'pipeline' => $pipeline->id, ]),
        );

        // Then
        Queue::assertPushedWithChain(ExecutePipeline::class, [
            ExecuteCreateRepositoryStep::class,
        ]);

        $response->assertRedirect(
            route('pipelines.show', [
                'pipeline' => $pipeline->id,
            ]),
        );
    }

    /** @test */
    public function a_pipeline_cannot_be_executed_unless_its_been_fully_configured()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this->post(
            route('pipelines.execute', [ 'pipeline' => $pipeline->id, ]),
        );

        // Then
        $response->assertRedirect(
            route('steps.configuration.render', [
                'pipeline' => $pipeline->id,
                'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
            ])
        );
    }

}
