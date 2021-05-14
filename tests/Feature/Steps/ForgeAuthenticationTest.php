<?php

namespace Tests\Feature\Steps;

use Tests\TestCase;
use App\Enums\StepType;
use Inertia\Testing\Assert;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgeAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_forge_authentication_step_page()
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
                'step' => StepType::FORGE_AUTHENTICATION,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->is('Pipeline/Steps/Laravel/ForgeAuthentication');
        });
    }

    /** @test */
    public function it_renders_existing_accounts()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'identifier' => 'Bjorn Lindholm',
            'user_id' => $user->id,
            'type' => 'forge',
        ]);

        // When
        $response = $this->get(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_AUTHENTICATION,
            ])
        );

        // Then
        $response->assertInertia(function (Assert $page) {
            $page->has('accounts', 1);
            $page->where('accounts.0.identifier', 'Bjorn Lindholm');
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
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'type' => 'forge',
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $this->assertDatabaseHas('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => 'forge-authentication',
            'details->account_id' => $account->id,
        ]);
    }

    /** @test */
    public function it_must_be_an_existing_forge_account()
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
                    'step' => StepType::FORGE_AUTHENTICATION,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_AUTHENTICATION,
                ]),
                [
                    'account_id' => 'fake_id', 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => StepType::FORGE_AUTHENTICATION,
            'details->account_id' => 'fake_id',
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_AUTHENTICATION,
            ]),
        );
        $response->assertSessionHasErrors('account_id');
    }

    /** @test */
    public function the_account_must_belong_to_a_user_in_the_team()
    {
        $this->withExceptionHandling();

        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'type' => 'forge',
        ]);

        // When
        $response = $this
            ->from(
                route('steps.configuration.render', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_AUTHENTICATION,
                ]),
            )
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $this->assertDatabaseMissing('step_configurations', [
            'pipeline_id' => $pipeline->id,
            'type' => StepType::FORGE_AUTHENTICATION,
            'details->account_id' => $account->id,
        ]);

        $response->assertRedirect(
            route('steps.configuration.render', [ 
                'pipeline' => $pipeline->id,
                'step' => StepType::FORGE_AUTHENTICATION,
            ]),
        );
        $response->assertSessionHasErrors('account_id');
    }

    /** @test */
    public function it_does_not_create_a_runnable_step()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'type' => 'forge',
        ]);

        // When
        $response = $this
            ->post(
                route('steps.configuration.configure', [ 
                    'pipeline' => $pipeline->id,
                    'step' => StepType::FORGE_AUTHENTICATION,
                ]),
                [
                    'account_id' => $account->id, 
                ]
            );

        // Then
        $config = StepConfiguration::where('type', StepType::FORGE_AUTHENTICATION)->first();
        $this->assertDatabaseMissing('steps', [
            'config_id' => $config->id,
        ]);
    }

}
