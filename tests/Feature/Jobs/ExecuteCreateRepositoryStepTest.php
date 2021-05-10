<?php

namespace Tests\Feature\Jobs;

use \Exception;
use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use Mockery\MockInterface;
use App\Enums\PipelineStatus;
use App\Jobs\ExecutePipeline;
use App\Models\Pipeline\Step;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Events\PipelineStepFailed;
use App\Events\PipelineStepRunning;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Events\PipelineStepSuccessful;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\ExecuteCreateRepositoryStep;
use App\Support\LaravelRepositoryCreator;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExecuteCreateRepositoryStepTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_dispatched_if_it_should_create_a_repository()
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
    }

    /** @test */
    public function it_runs_the_create_repository_service()
    {
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

        // Then
        $this->mock(
            LaravelRepositoryCreator::class,
            function (MockInterface $mock) use ($pipeline, $account) {
                $mock->shouldReceive('execute')
                    ->withArgs(function ($givenPipeline, $givenAccount) use ($pipeline, $account) {
                        return ($givenPipeline->id === $pipeline->id) &&
                            ($givenAccount->id === $account->id);
                    })
                    ->once();
            }
        );

        // When
        ExecuteCreateRepositoryStep::dispatch($pipeline);
    }

        /** @test */
    public function it_notifies_when_the_status_changes()
    {
        Event::fake([
            PipelineStepRunning::class,
            PipelineStepSuccessful::class,
        ]);

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

        // Then
        $this->mock(
            LaravelRepositoryCreator::class,
            function (MockInterface $mock) use ($pipeline, $account) {
                $mock->shouldReceive('execute')
                    ->withArgs(function ($givenPipeline, $givenAccount) use ($pipeline, $account) {
                        return ($givenPipeline->id === $pipeline->id) &&
                            ($givenAccount->id === $account->id);
                    })
                    ->once();
            }
        );

        // When
        ExecuteCreateRepositoryStep::dispatch($pipeline);
        
        // Then
        Event::assertDispatched(PipelineStepRunning::class);
        Event::assertDispatched(PipelineStepSuccessful::class);
    }


    /** @test */
    public function it_notifies_if_it_fails()
    {
        Event::fake([
            PipelineStepFailed::class,
        ]);

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => GitProvider::GITHUB,
            'user_id' => $user->id,
        ]);
        $stepOne = Step::factory()->create([
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
        $stepTwo = Step::factory()->create([
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
        $job = new ExecuteCreateRepositoryStep($pipeline);
        event(new JobFailed('sync', $job, new Exception));

        // Then
        Event::assertDispatched(PipelineStepFailed::class, function ($event) use ($pipeline) {
            return $pipeline->id === $event->pipeline->id;
        });
    }

}
