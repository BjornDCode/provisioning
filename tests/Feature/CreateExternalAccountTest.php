<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\StepType;
use Laravel\Socialite\Two\User;
use App\Models\Pipeline\Pipeline;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateExternalAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_provider()
    {
        // Given 
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create();

        // When
        $response = $this->get(
            route('accounts.redirect', [
                'provider' => 'github',
            ])
        );

        // Then
        $response->assertRedirect();
        $this->assertStringContainsString(
            'https://github.com/login/oauth/authorize',
            $response->headers->get('location')
        );
    }

    /** @test */
    public function it_saves_an_account()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        $oauthUser = new User();
        $oauthUser->name = 'Bjorn';
        $oauthUser->nickname = 'BjornUsername';
        $oauthUser->email = 'test@example.com';
        $oauthUser->token = 'test_token';

        $fromRoute = route('steps.configuration.render', [
            'pipeline' => $pipeline->id,
            'step' => StepType::GITHUB_AUTHENTICATION,
        ]);

        $this->mockSocialite(
            'github',
            $oauthUser,
            [
                'redirect' => $fromRoute,
            ],
            [
                'read:user',
                'repo',
                'delete_repo',
            ]
        );

        // When
        $response = $this
            ->followingRedirects()
            ->from($fromRoute)
            ->get(
                route('accounts.redirect', [
                    'provider' => 'github',
                ])
            );

        // Then
        $this->assertDatabaseHas('accounts', [
            'identifier' => 'BjornUsername',
            'email' => 'test@example.com',
            'user_id' => $user->id,
            'type' => 'github',
        ]);
        // $response->assertLocation($fromRoute);
    }

}
