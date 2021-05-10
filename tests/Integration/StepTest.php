<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Pipeline\Step;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StepTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_the_same_type_as_its_config()
    {
        // Given
        $config = StepConfiguration::factory()->create([
            'type' => StepType::GITHUB_AUTHENTICATION,
            'pipeline_id' => Pipeline::factory()->create()->id,
            'details' => [
                'account_id' => Account::factory()->create()->id,
            ],
        ]);
        $step = Step::factory()->create([
            'config_id' => $config->id,
        ]);

        // When
        $type = $step->type;

        // Then
        $this->assertEquals('github-authentication', $type);
    }

}
