<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use App\Flows\Laravel\Flow;
use App\Models\Pipeline\Pipeline;
use App\Steps\Factory as StepFactory;
use App\Steps\Shared\GithubAuthentication;
use App\Steps\Shared\GitProvider as GitProviderStep;

class StepFactoryTest extends TestCase
{

    /** @test */
    public function it_can_instantiate_a_git_provider_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            GitProviderStep::class, 
            StepFactory::create(
                StepType::fromString('git-provider'),
                $flow,
            )
        );
    }
    /** @test */
    public function it_can_instantiate_a_github_authentication_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            GithubAuthentication::class, 
            StepFactory::create(
                StepType::fromString('github-authentication'),
                $flow,
            )
        );
    }

}
