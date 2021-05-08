<?php

namespace Tests\Unit;

use App\Enums\PipelineType;
use PHPUnit\Framework\TestCase;
use App\Exceptions\InvalidTypeException;

class PipelineTypeTest extends TestCase
{
    /** @test */
    public function it_can_be_laravel()
    {
        $this->assertEquals('laravel', PipelineType::LARAVEL);
    }

    /** @test */
    public function it_can_return_all_types()
    {
        $this->assertEquals([
            'laravel',
        ], PipelineType::all());
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(PipelineType::class, PipelineType::fromString('laravel'));
    }

    /** @test */
    public function it_validates_the_type()
    {
        $this->expectException(InvalidTypeException::class);

        PipelineType::fromString('invalid');
    }

    /** @test */
    public function it_can_convert_to_string()
    {
        $type = PipelineType::fromString('laravel');
        
        $this->assertEquals('laravel', $type->toString());
    }

}
