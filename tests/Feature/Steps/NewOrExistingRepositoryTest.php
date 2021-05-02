<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Project;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewOrExistingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_git_provider_step_page()
    {
        // Given
        $user = $this->registerNewUser();
        $project = Project::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'project' => $project->id,
                'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/NewOrExistingRepository');
        });
    }

    /** @test */
    public function it_can_save_the_configuration()
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
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
                [
                    'value' => 'new', 
                ]
            );

        // Then
        $this->assertDatabaseHas('step_configurations', [
            'project_id' => $project->id,
            'type' => 'new-or-existing-repository',
            'details->value' => 'new',
        ]);
    }

    /** @test */
    public function it_must_be_a_valid_answer()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $project = Project::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'project' => $project->id,
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'project' => $project->id,
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
                [
                    'value' => 'invalid', 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'project_id' => $project->id,
            'type' => 'new-or-existing-repository',
            'details->value' => 'invalid',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'project' => $project->id,
                'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
            ]),
        );
        $response->assertSessionHasErrors('value');
    }

}
