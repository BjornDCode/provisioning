<?php

namespace Tests\Unit;

use App\Enums\StepType;
use PHPUnit\Framework\TestCase;
use App\Exceptions\InvalidTypeException;

class StepTypeTest extends TestCase
{
    /** @test */
    public function it_can_be_new_or_existing()
    {
        $this->assertEquals('new-or-existing-repository', StepType::NEW_OR_EXISTING_REPOSITORY);
    }

    /** @test */
    public function it_can_be_git_provider()
    {
        $this->assertEquals('git-provider', StepType::GIT_PROVIDER);
    }

    /** @test */
    public function it_can_be_github_authentication()
    {
        $this->assertEquals('github-authentication', StepType::GITHUB_AUTHENTICATION);
    }

    /** @test */
    public function it_can_be_choose_repository()
    {
        $this->assertEquals('choose-repository', StepType::CHOOSE_REPOSITORY);
    }

    /** @test */
    public function it_can_be_hosting_prompt()
    {
        $this->assertEquals('hosting', StepType::HOSTING_PROMPT);
    }

    /** @test */
    public function it_can_be_environments()
    {
        $this->assertEquals('environments', StepType::ENVIRONMENTS);
    }

    /** @test */
    public function it_can_be_forge_authentication()
    {
        $this->assertEquals('forge-authentication', StepType::FORGE_AUTHENTICATION);
    }

    /** @test */
    public function it_can_be_forge_server_provider()
    {
        $this->assertEquals('forge-server-provider', StepType::FORGE_SERVER_PROVIDER);
    }

    /** @test */
    public function it_can_be_server_configuration()
    {
        $this->assertEquals('server-configuration', StepType::SERVER_CONFIGURATION);
    }

    /** @test */
    public function it_can_return_all_types()
    {
        $this->assertEquals([
            'new-or-existing-repository',
            'git-provider',
            'github-authentication',
            'choose-repository',
            'hosting',
            'environments',
            'forge-authentication',
            'forge-server-provider',
            'server-configuration',
        ], StepType::all());
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(StepType::class, StepType::fromString('git-provider'));
    }

    /** @test */
    public function it_validates_the_type()
    {
        $this->expectException(InvalidTypeException::class);

        StepType::fromString('invalid');
    }

    /** @test */
    public function it_can_convert_to_string()
    {
        $type = StepType::fromString('git-provider');
        
        $this->assertEquals('git-provider', $type->toString());
    }

}
