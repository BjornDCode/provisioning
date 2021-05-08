<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PipelineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_its_git_provider_if_it_exists()
    {
        // Given
        $pipelineWithConfig = Pipeline::factory()->create();
        $pipelineWithoutConfig = Pipeline::factory()->create();

        StepConfiguration::factory()->create([
            'pipeline_id' => $pipelineWithConfig->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'laravel',
            ],
        ]);

        // When
        // Then
        $this->assertEquals('laravel', $pipelineWithConfig->gitProvider);
        $this->assertNull($pipelineWithoutConfig->gitProvider);
    }

    /** @test */
    public function it_knows_whether_it_has_a_config_for_a_certain_step_type()
    {
        // Given
        $pipeline = Pipeline::factory()->create();

        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'laravel',
            ],
        ]);

        // When
        // Then
        $this->assertTrue(
            $pipeline->hasConfig(StepType::fromString('git-provider'))
        );
        $this->assertFalse(
            $pipeline->hasConfig(StepType::fromString('new-or-existing-repository'))
        );
    }


    /** @test */
    public function it_can_get_a_config_for_a_certain_step_type()
    {
        // Given
        $pipeline = Pipeline::factory()->create();

        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'laravel',
            ],
        ]);

        // When
        // Then
        $this->assertEquals(
            'laravel',
            $pipeline->getConfig(StepType::fromString('git-provider'))['details']['value']
        );
        $this->assertNull(
            $pipeline->getConfig(StepType::fromString('new-or-existing-repository'))
        );
    }

}
