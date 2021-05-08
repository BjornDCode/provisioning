<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Pipeline;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePipelinesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_cannot_create_teams()
    {
        $this->withExceptionHandling();

        // Given
        // When
        $response = $this->post(
            route('pipelines.store'),
            [
                'name' => 'Cool pipeline',
                'type' => 'laravel',
            ]
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_create_pipelines_for_teams_they_are_a_member_of()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('pipelines.store'),
            [
                'name' => 'Cool pipeline',
                'type' => 'laravel',
            ]
        );

        // Then
        $this->assertDatabaseHas('pipelines', [
            'name' => 'Cool pipeline',
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
            ->from(route('pipelines.create'))
            ->post(
                route('pipelines.store'),
                [
                    'name' => '',
                    'type' => 'laravel',
                ]
            );

        // Then
        $response->assertRedirect(route('pipelines.create'));
        $response->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('pipelines', [
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
            ->from(route('pipelines.create'))
            ->post(
                route('pipelines.store'),
                [
                    'name' => 'Cool pipeline',
                    'type' => '',
                ]
            );

        // Then
        $response->assertRedirect(route('pipelines.create'));
        $response->assertSessionHasErrors('type');

        $this->assertDatabaseMissing('pipelines', [
            'team_id' => $user->currentTeam->id,
        ]);
    }

    /** @test */
    public function it_has_to_be_a_valid_pipeline_type()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this
            ->from(route('pipelines.create'))
            ->post(
                route('pipelines.store'),
                [
                    'name' => 'Cool pipeline',
                    'type' => 'invalid-pipeline-type',
                ]
            );

        // Then
        $response->assertRedirect(route('pipelines.create'));
        $response->assertSessionHasErrors('type');

        $this->assertDatabaseMissing('pipelines', [
            'team_id' => $user->currentTeam->id,
        ]);
    }

    /** @test */
    public function it_can_create_laravel_pipelines()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('pipelines.store'),
            [
                'name' => 'Cool pipeline',
                'type' => 'laravel',
            ]
        );

        // Then
        $this->assertDatabaseHas('pipelines', [
            'name' => 'Cool pipeline',
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
        $response = $this->get(route('pipelines.create'));

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Create');
        });
    }

    /** @test */
    public function it_redirects_to_the_first_step_in_the_flow_after_creation()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('pipelines.store'),
            [
                'name' => 'Cool pipeline',
                'type' => 'laravel',
            ]
        );

        // Then
        $pipeline = Pipeline::first();
        $response->assertRedirect(
            route('steps.configuration.render', [
                'pipeline' => $pipeline->id,
                'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
            ])
        );
    }

}
