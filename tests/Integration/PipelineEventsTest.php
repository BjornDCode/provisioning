<?php

namespace Tests\Integration;

use Exception;
use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use App\Enums\PipelineStatus;
use App\Models\Pipeline\Step;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Events\PipelineStepFailed;
use App\Events\PipelineStepRunning;
use Illuminate\Support\Facades\Event;
use App\Events\PipelineStepSuccessful;
use Illuminate\Queue\Events\JobFailed;
use App\Jobs\ExecuteCreateRepositoryStep;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PipelineEventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cancels_the_remaining_pending_steps_when_a_pipeline_fails()
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

        // When
        PipelineStepFailed::dispatch($pipeline, $stepOne);

        // Then
        $this->assertDatabaseHas('steps', [
            'id' => $stepTwo->id,
            'status' => 'cancelled',
        ]);
    }

    /** @test */
    public function it_sets_the_failed_steps_status_to_failed()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
        ]);

        // When
        PipelineStepFailed::dispatch($pipeline, $step);

        // Then
        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
            'status' => 'failed',
        ]);
    }

    /** @test */
    public function it_marks_the_step_as_running()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
        ]);

        // When
        PipelineStepRunning::dispatch($pipeline, $step);

        // Then
        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
            'status' => 'running',
        ]);
    }

    /** @test */
    public function it_marks_the_step_as_successful()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $step = Step::factory()->create([
            'title' => 'Create repository',
            'status' => PipelineStatus::PENDING,
        ]);

        // When
        PipelineStepSuccessful::dispatch($pipeline, $step);

        // Then
        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
            'status' => 'successful',
        ]);
    }

}
