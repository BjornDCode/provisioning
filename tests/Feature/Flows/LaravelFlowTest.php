<?php

namespace Tests\Feature\Flows;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaravelFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_git_provider_step_after_creating_a_project()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('projects.store'),
            [
                'name' => 'Cool project',
                'type' => 'laravel',
            ]
        );

        // Then
        $project = Project::first();
        $response->assertRedirect(
            route('steps.configuration.render', [
                'project' => $project->id,
                'step' => 'git-provider',
            ])
        );
    }

    /** @test */
    public function it_redirects_to_the_github_authenication_step()
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
        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'project' => $project->id,
                'step' => 'github-account',
            ])
        );
    }

}
