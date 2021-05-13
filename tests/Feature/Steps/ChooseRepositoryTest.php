<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use Inertia\Testing\Assert;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Clients\Github\ApiClient as GithubApiClient;
use App\Clients\Github\TestApiClient as GithubTestApiClient;

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
        $this->app->bind(GithubApiClient::class, GithubTestApiClient::class);
        
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => GitProvider::GITHUB,
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::GIT_PROVIDER,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'value' => GitProvider::GITHUB,
            ],
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::GITHUB_AUTHENTICATION,
            'pipeline_id' => $pipeline->id,
            'details' => [
                'account_id' => $account->id,
            ],
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
            $page->has('repositories', 3);
            $page->where('repositories.0.owner', env('GITHUB_ACCOUNT_NAME'));
            $page->where('repositories.0.name', 'aaaaaa');
        });
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
