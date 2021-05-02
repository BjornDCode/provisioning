<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_see_projects()
    {
        $this->withExceptionHandling();

        // Given
        // When
        $response = $this->get(route('projects.index'));

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_view_their_teams_projects()
    {
        // Given
        $user = $this->registerNewUser();

        $project = Project::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this->get(route('projects.index'));


        // Then
        $response->assertInertia(function (Assert $page) use ($project) {
            $page->is('Pipeline/Projects/Index');

            $page->has('projects', 1);
            $page->has('projects', function (Assert $projects) use ($project) {
                $projects->where('0.id', $project->id);
            });
        });
    }

}
