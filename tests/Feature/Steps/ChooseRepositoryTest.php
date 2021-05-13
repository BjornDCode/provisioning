<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use Inertia\Testing\Assert;
use App\Models\Pipeline\Pipeline;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChooseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_choose_repository_step_page()
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
                'step' => StepType::CHOOSE_REPOSITORY,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/ChooseRepository');
        });
    }

    /** @test */
    public function it_lists_repositories()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_save_the_configuration()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_repository_owner_is_required()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_repository_name_is_required()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_does_not_create_a_runnable_step()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_removes_the_config_if_the_flow_is_changed_to_a_new_reposity()
    {
        $this->markTestIncomplete();
    }

}
