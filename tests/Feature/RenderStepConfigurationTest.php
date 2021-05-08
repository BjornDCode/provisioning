<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\GitProvider;
use Inertia\Testing\Assert;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RenderStepConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_authenticated_user_can_not_render_the_page_to_configure_a_step()
    {
        $this->withExceptionHandling();

        // Given
        $pipeline = Pipeline::factory()->create();

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => 'git-provider',
            ])
        );

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_cannot_render_the_page_to_configure_a_step_for_anoter_teams_pipeline()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create();

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => 'git-provider',
            ])
        );

        // Then
        $response->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_render_the_page_to_configure_a_step()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => 'git-provider',
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/GitProvider');
        });
    }

    /** @test */
    public function it_renders_the_page_with_existing_data_if_the_step_has_already_been_configured()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $configuration = StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => 'git-provider',
            'details' => [
                'value' => GitProvider::GITHUB,
            ],
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => 'git-provider',
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) use ($configuration) {
            $page->is('Pipeline/Steps/GitProvider');

            $page->where('configuration.id', $configuration->id);
        });
    }
}
