<?php

namespace Tests\Feature\Flows;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaravelFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_new_or_existing_step_after_creating_a_pipeline()
    {
        // Given
        $user = $this->registerNewUser();

        // When
        $response = $this->post(
            route('pipelines.store'),
            [
                'name' => 'Cool pipeline',
                'type' => 'laravel',
            ]
        );

        // Then
        $pipeline = Pipeline::first();
        $response->assertRedirect(
            route('steps.configuration.render', [
                'pipeline' => $pipeline->id,
                'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
            ])
        );
    }

    /** @test */
    public function it_redirects_to_git_provider_step()
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
                    'step' => StepType::NEW_OR_EXISTING_REPOSITORY,
                ]),
                [
                    'value' => 'new', 
                ]
            );

        // Then
        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::GIT_PROVIDER,
            ])
        );
    }

    /** @test */
    public function it_redirects_to_the_github_authenication_step()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'details' => [
                'value' => 'new',
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GIT_PROVIDER,
                ]),
                [
                    'value' => 'github', 
                ]
            );

        // Then
        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::GITHUB_AUTHENTICATION,
            ])
        );
    }

    /** @test */
    public function it_redirects_to_the_choose_repository_step()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'details' => [
                'value' => 'existing',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'github',
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::CHOOSE_REPOSITORY,
            ])
        );
    }

    /** @test */
    public function it_redirects_to_the_hosting()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'details' => [
                'value' => 'new',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'github',
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::GITHUB_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::HOSTING_PROMPT,
            ])
        );    
    }

    /** @test */
    public function it_finishes_the_flow_if_no_hosting()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'details' => [
                'value' => 'new',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'github',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::HOSTING_PROMPT,
                ]),
                [
                    'value' => false, 
                ]
            );

        // Then
        $response->assertRedirect(
            route('pipelines.show', [ 
                'pipeline' => $pipeline->id,
            ])
        );
    }

    /** @test */
    public function it_redirects_to_environments()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'details' => [
                'value' => 'new',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GIT_PROVIDER,
            'details' => [
                'value' => 'github',
            ],
        ]);
        StepConfiguration::factory()->create([
            'pipeline_id' => $pipeline->id,
            'type' => StepType::GITHUB_AUTHENTICATION,
            'details' => [
                'account_id' => $account->id,
            ],
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::HOSTING_PROMPT,
                ]),
                [
                    'value' => true, 
                ]
            );

        // Then
        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::ENVIRONMENTS,
            ])
        );    
    }

    // /** @test */
    // public function it_redirects_the_the_pipeline_overview_page()
    // {
    //     // Given
    //     $user = $this->registerNewUser();
    //     $pipeline = Pipeline::factory()->create([
    //         'team_id' => $user->currentTeam->id,
    //     ]);
    //     $account = Account::factory()->create([
    //         'user_id' => $user->id,
    //     ]);
    //     StepConfiguration::factory()->create([
    //         'pipeline_id' => $pipeline->id,
    //         'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
    //         'details' => [
    //             'value' => 'new',
    //         ],
    //     ]);
    //     StepConfiguration::factory()->create([
    //         'pipeline_id' => $pipeline->id,
    //         'type' => StepType::GIT_PROVIDER,
    //         'details' => [
    //             'value' => 'github',
    //         ],
    //     ]);

    //     // When
    //     $response = $this
    //         ->post(
    //             route('steps.configuration.configure', [ 
    //                 'pipeline' => $pipeline->id,
    //                 'step' => StepType::GITHUB_AUTHENTICATION,
    //             ]),
    //             [
    //                 'account_id' => $account->id, 
    //             ]
    //         );

    //     // Then
    //     $response->assertRedirect(
    //         route('pipelines.show', [ 
    //             'pipeline' => $pipeline->id,
    //         ])
    //     );
    // }

}
