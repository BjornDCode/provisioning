<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\StepConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfigureStepsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_configure_a_step()
    {
        $this->withExceptionHandling();

        // Given
        $project = Project::factory()->create();

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'project' => $project->id,
                    'step' => 'git-provider',
                ]),
                [
                    'value' => 'github', 
                ]
            );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_cannot_configure_a_step_for_another_teams_project()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $project = Project::factory()->create();

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'project' => $project->id,
                    'step' => 'git-provider',
                ]),
                [
                    'value' => 'github', 
                ]
            );

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_step_configuration()
    {
        // Given
        $user = $this->registerNewUser();
        $project = Project::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'project' => $project->id,
                    'step' => 'git-provider',
                ]),
                [
                    'value' => 'github', 
                ]
            );

        // Then
        $this->assertDatabaseHas('step_configurations', [
            'project_id' => $project->id,
            'type' => 'git-provider',
            'details->value' => 'github',
        ]);
    }

    /** @test */
    public function an_authenticated_user_can_update_an_existing_configuration()
    {
        // Given
        $user = $this->registerNewUser();
        $project = Project::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $configuration = StepConfiguration::factory()->create([
            'project_id' => $project->id,
            'type' => 'git-provider',
            'details' => [
                'value' => 'gitlab',
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'project' => $project->id,
                    'step' => 'git-provider',
                ]),
                [
                    'value' => 'github', 
                ]
            );

        // Then
        $this->assertDatabaseHas('step_configurations', [
            'id' => $configuration->id,
            'project_id' => $project->id,
            'type' => 'git-provider',
            'details->value' => 'github',
        ]);
    }

}
