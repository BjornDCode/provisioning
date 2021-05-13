<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use App\Enums\PipelineStatus;
use App\Models\Pipeline\Step;
use App\Models\Pipeline\Account;
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
                'value' => 'github',
            ],
        ]);

        // When
        // Then
        $this->assertEquals('github', $pipelineWithConfig->gitProvider);
        $this->assertNull($pipelineWithoutConfig->gitProvider);
    }

    /** @test */
    public function it_can_return_its_git_account_if_it_exists()
    {
        // Given
        $pipelineWithConfig = Pipeline::factory()->create();
        $pipelineWithoutConfig = Pipeline::factory()->create();

        $account = Account::factory()->create([
            'type' => GitProvider::GITHUB,
        ]);

        StepConfiguration::factory()->create([
            'pipeline_id' => $pipelineWithConfig->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => GitProvider::GITHUB,
            ],
        ]);

        StepConfiguration::factory()->create([
            'pipeline_id' => $pipelineWithConfig->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        // Then
        $this->assertEquals($account->id, $pipelineWithConfig->gitAccount->id);
        $this->assertNull($pipelineWithoutConfig->gitAccount);
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

    /** @test */
    public function its_status_is_pending_if_all_its_steps_are_pending()
    {
        // Given
        $pipeline = Pipeline::factory()->create();

        Step::factory()->count(3)->create([
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        // When
        $status = $pipeline->status;

        // Then
        $this->assertEquals('pending', $status);
    }

    /** @test */
    public function its_status_is_running_if_one_step_is_running()
    {
        // Given
        $pipeline = Pipeline::factory()->create();

        Step::factory()->create([
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::RUNNING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::SUCCESSFUL,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        // When
        $status = $pipeline->status;

        // Then
        $this->assertEquals('running', $status);
    }

    /** @test */
    public function its_status_is_failed_if_one_step_is_failed()
    {
        // Given
        $pipeline = Pipeline::factory()->create();

        Step::factory()->create([
            'status' => PipelineStatus::PENDING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::RUNNING,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::SUCCESSFUL,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::FAILED,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        // When
        $status = $pipeline->status;

        // Then
        $this->assertEquals('failed', $status);
    }

    /** @test */
    public function its_status_is_successful_if_all_its_steps_are_successful()
    {
        // Given
        $pipeline = Pipeline::factory()->create();

        Step::factory()->count(3)->create([
            'status' => PipelineStatus::SUCCESSFUL,
            'config_id' => StepConfiguration::factory()->create([
                'pipeline_id' => $pipeline->id,
            ]),
        ]);

        // When
        $status = $pipeline->status;

        // Then
        $this->assertEquals('successful', $status);
    }

}
