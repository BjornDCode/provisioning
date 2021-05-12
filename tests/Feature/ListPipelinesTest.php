<?php

namespace Tests\Feature;

use Tests\TestCase;
use Inertia\Testing\Assert;
use App\Enums\PipelineStatus;
use App\Models\Pipeline\Step;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListPipelinesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_cannot_list_pipelines()
    {
        $this->withExceptionHandling();

        // Given
        // When
        $response = $this->get(route('pipelines.index'));

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_list_their_teams_pipelines()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        Pipeline::factory()->create();

        // When
        $response = $this->get(route('pipelines.index'));

        // Then
        $response->assertInertia(function (Assert $page) use ($pipeline) {
            $page->is('Pipeline/Index');

            $page->where('pending.0.id', $pipeline->id);
        });
    }

    /** @test */
    public function pipelines_are_grouped_by_status()
    {
        // Given
        $user = $this->registerNewUser();
        $pendingPipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $runningPipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $failedPipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $successfulPipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pendingPipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::RUNNING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $runningPipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::FAILED,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $failedPipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::SUCCESSFUL,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $successfulPipeline->id,
            ]),
        ]);

        // When
        $response = $this->get(route('pipelines.index'));

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->has('pending', 1);
            $page->has('running', 1);
            $page->has('failed', 1);
            $page->has('successful', 1);
        });
    }

}
