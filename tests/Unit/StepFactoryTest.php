<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use App\Flows\Laravel\Flow;
use App\Models\Pipeline\Pipeline;
use App\Steps\Factory as StepFactory;
use App\Steps\Shared\ChooseRepository;
use App\Steps\Shared\GithubAuthentication;
use App\Steps\Shared\NewOrExistingRepository;
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
    
    /** @test */
    public function it_can_instantiate_a_new_or_existing_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            NewOrExistingRepository::class, 
            StepFactory::create(
                StepType::fromString('new-or-existing-repository'),
                $flow,
            )
        );
    }
    
    /** @test */
    public function it_can_instantiate_a_choose_repository_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            ChooseRepository::class, 
            StepFactory::create(
                StepType::fromString('choose-repository'),
                $flow,
            )
        );
    }

}
