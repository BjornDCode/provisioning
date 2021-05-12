<?php

namespace Tests\Unit;

use App\Enums\PipelineStatus;
use PHPUnit\Framework\TestCase;
use App\Exceptions\InvalidTypeException;

class PipelineStatusTest extends TestCase
{

    /** @test */
    public function it_can_be_pending()
    {
        $this->assertEquals('pending', PipelineStatus::PENDING);
    }

    /** @test */
    public function it_can_be_running()
    {
        $this->assertEquals('running', PipelineStatus::RUNNING);
    }

    /** @test */
    public function it_can_be_failed()
    {
        $this->assertEquals('failed', PipelineStatus::FAILED);
    }

    /** @test */
    public function it_can_be_successful()
    {
        $this->assertEquals('successful', PipelineStatus::SUCCESSFUL);
    }

    /** @test */
    public function it_can_be_cancelled()
    {
        $this->assertEquals('cancelled', PipelineStatus::CANCELLED);
    }

    /** @test */
    public function it_can_return_all_types()
    {
        $this->assertEquals([
            'pending',
            'running',
            'failed',
            'successful',
            'cancelled',
        ], PipelineStatus::all());
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(PipelineStatus::class, PipelineStatus::fromString('pending'));
    }

    /** @test */
    public function it_validates_the_type()
    {
        $this->expectException(InvalidTypeException::class);

        PipelineStatus::fromString('invalid');
    }

    /** @test */
    public function it_can_convert_to_string()
    {
        $type = PipelineStatus::fromString('pending');
        
        $this->assertEquals('pending', $type->toString());
    }

}
