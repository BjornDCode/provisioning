<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Enums\StepType;
use App\Enums\GitProvider;
use App\Flows\Laravel\Flow;
use App\Models\Pipeline\Pipeline;
use App\Steps\Laravel\Environments;
use App\Steps\Laravel\HostingPrompt;
use App\Steps\Factory as StepFactory;
use App\Steps\Shared\ChooseRepository;
use App\Steps\Laravel\ForgeAuthentication;
use App\Steps\Laravel\ForgeServerProvider;
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

    /** @test */
    public function it_can_instantiate_a_hosting_prompt_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            HostingPrompt::class, 
            StepFactory::create(
                StepType::fromString('hosting'),
                $flow,
            )
        );
    }

    /** @test */
    public function it_can_instantiate_an_environments_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            Environments::class, 
            StepFactory::create(
                StepType::fromString('environments'),
                $flow,
            )
        );
    }

    /** @test */
    public function it_can_instantiate_a_forge_authentication_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            ForgeAuthentication::class, 
            StepFactory::create(
                StepType::fromString('forge-authentication'),
                $flow,
            )
        );
    }

    /** @test */
    public function it_can_instantiate_a_forge_server_provider_step()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $flow = new Flow($pipeline);

        $this->assertInstanceOf(
            ForgeServerProvider::class, 
            StepFactory::create(
                StepType::fromString('forge-server-provider'),
                $flow,
            )
        );
    }

}
