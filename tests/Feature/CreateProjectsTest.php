<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_cannot_create_teams()
    {
        $this->withExceptionHandling();

        // Given
        // When
        $response = $this->post(
            route('projects.store'),
            [
                'name' => 'Cool project',
                'type' => 'laravel',
            ]
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_projects_for_teams_they_are_a_member_of()
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
        $this->assertDatabaseHas('projects', [
            'name' => 'Cool project',
            'type' => 'laravel',
            'team_id' => $user->currentTeam->id,
        ]);
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(route('projects.create'))
            ->post(
                route('projects.store'),
                [
                    'name' => '',
                    'type' => 'laravel',
                ]
            );

        // Then
        $response->assertRedirect(route('projects.create'));
        $response->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('projects', [
            'team_id' => $user->currentTeam->id,
        ]);
    }

    /** @test */
    public function type_is_required()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(route('projects.create'))
            ->post(
                route('projects.store'),
                [
                    'name' => 'Cool project',
                    'type' => '',
                ]
            );

        // Then
        $response->assertRedirect(route('projects.create'));
        $response->assertSessionHasErrors('type');

        $this->assertDatabaseMissing('projects', [
            'team_id' => $user->currentTeam->id,
        ]);
    }

    /** @test */
    public function it_has_to_be_a_valid_project_type()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(route('projects.create'))
            ->post(
                route('projects.store'),
                [
                    'name' => 'Cool project',
                    'type' => 'invalid-project-type',
                ]
            );

        // Then
        $response->assertRedirect(route('projects.create'));
        $response->assertSessionHasErrors('type');

        $this->assertDatabaseMissing('projects', [
            'team_id' => $user->currentTeam->id,
        ]);
    }

    /** @test */
    public function it_can_create_laravel_projects()
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
        $this->assertDatabaseHas('projects', [
            'name' => 'Cool project',
            'type' => 'laravel',
            'team_id' => $user->currentTeam->id,
        ]);
    }

    /** @test */
    public function it_can_render_the_create_page()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->get(route('projects.create'));

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Projects/Create');
        });
    }

    /** @test */
    public function it_redirects_to_the_first_step_in_the_flow_after_creation()
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
            route('steps.configure', [
                'project' => $project->id,
                'step' => 'git-provider',
            ])
        );
    }

}
