<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use Inertia\Testing\Assert;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnvironmentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_environments_step_page()
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
                'step' => StepType::ENVIRONMENTS,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/Laravel/Environments');
        });
    }

    /** @test */
    public function it_can_save_the_configuration()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
                [
                    'value' => [
                        'Staging',
                        'Production',
                    ], 
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::ENVIRONMENTS)->first();
        $this->assertDatabaseHas('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'environments',
        ]);
        $this->assertEquals('Staging', $config->details['value'][0]);
        $this->assertEquals('Production', $config->details['value'][1]);
    }

    /** @test */
    public function it_must_be_an_array()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
                [
                    'value' => 'invalid', 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'environments',
            'details->value' => 'invalid',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::ENVIRONMENTS,
            ]),
        );
        $response->assertSessionHasErrors('value');
    }

    /** @test */
    public function environments_must_be_unique()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
                [
                    'value' => [
                        'Staging',
                        'Staging',
                    ], 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'environments',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::ENVIRONMENTS,
            ]),
        );
        $response->assertSessionHasErrors('value.0');
    }

    /** @test */
    public function it_must_have_at_least_one_environment()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
                [
                    'value' => [], 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'environments',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::ENVIRONMENTS,
            ]),
        );
        $response->assertSessionHasErrors('value');
    }

    /** @test */
    public function it_does_not_create_a_runnable_step()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::ENVIRONMENTS,
                ]),
                [
                    'value' => [
                        'Staging',
                        'Production',
                    ], 
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::ENVIRONMENTS)->first();
        $this->assertDatabaseMissing('steps', [
            'config_id' => $config->id,
        ]);
    }

}
