<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Project;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GithubAuthenticationTest extends TestCase
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
                'step' => StepType::GITHUB_AUTHENTICATION,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/GithubAuthentication');
        });
    }

    /** @test */
    public function it_can_save_the_configuration()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_must_be_a_valid_git_provider()
    {
        $this->markTestIncomplete();
    }

}
