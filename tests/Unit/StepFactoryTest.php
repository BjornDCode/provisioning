<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Enums\StepType;
use App\Models\Project;
use App\Enums\GitProvider;
use App\Flows\Laravel\Flow;
use App\Steps\Factory as StepFactory;
use App\Steps\Shared\GithubAuthentication;
use App\Steps\Shared\GitProvider as GitProviderStep;

class StepFactoryTest extends TestCase
{

    /** @test */
    public function it_can_instantiate_a_git_provider_step()
    {
        $project = new Project([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($project);

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
        $project = new Project([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($project);

        $this->assertInstanceOf(
            GithubAuthentication::class, 
            StepFactory::create(
                StepType::fromString('github-authentication'),
                $flow,
            )
        );
    }

}
