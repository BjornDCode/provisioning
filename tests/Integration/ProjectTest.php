<?php

namespace Tests\Integration;

use Tests\TestCase;
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
            'type' => 'git-provider',
            'details' => [
                'value' => 'laravel',
            ],
        ]);
        // When
        // Then
        $this->assertEquals('laravel', $projectWithConfig->gitProvider);
        $this->assertNull($projectWithoutConfig->gitProvider);
    }

}
