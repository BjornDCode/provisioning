<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use App\Steps\Factory as StepFactory;
use App\Steps\Shared\GithubAuthentication;
use App\Steps\Shared\GitProvider as GitProviderStep;

class StepFactoryTest extends TestCase
{

    /** @test */
    public function it_can_instantiate_a_git_provider_step()
    {
        $this->assertInstanceOf(
            GitProviderStep::class, 
            StepFactory::create(StepType::fromString('git-provider'))
        );
    }
    /** @test */
    public function it_can_instantiate_a_github_authentication_step()
    {
        $this->assertInstanceOf(
            GithubAuthentication::class, 
            StepFactory::create(StepType::fromString('github-authentication'))
        );
    }

}
