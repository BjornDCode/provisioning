<?php

namespace Tests\Unit;

use App\Enums\ProjectType;
use PHPUnit\Framework\TestCase;
use App\Exceptions\InvalidProjectTypeException;

class ProjectTypeTest extends TestCase
{
    /** @test */
    public function it_can_be_laravel()
    {
        $this->assertEquals('laravel', ProjectType::LARAVEL);
    }

    /** @test */
    public function it_can_return_all_types()
    {
        $this->assertEquals([
            'laravel',
        ], ProjectType::all());
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ProjectType::class, ProjectType::fromString('laravel'));
    }

    /** @test */
    public function it_validates_the_type()
    {
        $this->expectException(InvalidProjectTypeException::class);

        ProjectType::fromString('invalid');
    }

    /** @test */
    public function it_can_convert_to_string()
    {
        $type = ProjectType::fromString('laravel');
        
        $this->assertEquals('laravel', $type->toString());
    }

}
