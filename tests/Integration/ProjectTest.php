<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Project;
use App\Models\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_its_git_provider_if_it_exists()
    {
        // Given
        $projectWithConfig = Project::factory()->create();
        $projectWithoutConfig = Project::factory()->create();

        StepConfiguration::factory()->create([
            'project_id' => $projectWithConfig->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'laravel',
            ],
        ]);

        // When
        // Then
        $this->assertEquals('laravel', $projectWithConfig->gitProvider);
        $this->assertNull($projectWithoutConfig->gitProvider);
    }

    /** @test */
    public function it_knows_whether_it_has_a_config_for_a_certain_step_type()
    {
        // Given
        $project = Project::factory()->create();

        StepConfiguration::factory()->create([
            'project_id' => $project->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'laravel',
            ],
        ]);

        // When
        // Then
        $this->assertTrue(
            $project->hasConfig(StepType::fromString('git-provider'))
        );
        $this->assertFalse(
            $project->hasConfig(StepType::fromString('new-or-existing-repository'))
        );
    }


    /** @test */
    public function it_can_get_a_config_for_a_certain_step_type()
    {
        // Given
        $project = Project::factory()->create();

        StepConfiguration::factory()->create([
            'project_id' => $project->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'laravel',
            ],
        ]);

        // When
        // Then
        $this->assertEquals(
            'laravel',
            $project->getConfig(StepType::fromString('git-provider'))['details']['value']
        );
        $this->assertNull(
            $project->getConfig(StepType::fromString('new-or-existing-repository'))
        );
    }

}
