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

class ShowPipelineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_cannot_see_a_pipeline()
    {
        $this->withExceptionHandling();

        // Given
        $pipeline = Pipeline::factory()->create();

        // When
        $response = $this->get(
            route('pipelines.show', [ 'pipeline' => $pipeline->id, ]),
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_cannot_see_another_teams_pipeline()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create();

        // When
        $response = $this->get(
            route('pipelines.show', [ 'pipeline' => $pipeline->id, ]),
        );

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_see_their_own_teams_pipeline()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        $stepOne = Step::factory()->create([
            'status' => PipelineStatus::SUCCESSFUL,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ])->id,
        ]);

        $stepTwo = Step::factory()->create([
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ])->id,
        ]);

        // When
        $response = $this->get(
            route('pipelines.show', [ 'pipeline' => $pipeline->id, ]),
        );

        // Then
        $response->assertInertia(function (Assert $page) use ($stepOne, $stepTwo) {
            $page->is('Pipeline/Show');

            $page->where('steps.0.id', $stepOne->id);
            $page->where('steps.0.status', 'successful');
            $page->where('steps.1.id', $stepTwo->id);
            $page->where('steps.1.status', 'pending');
        });
    }

}
