<?php

namespace Tests\Unit;

use App\Enums\ProjectType;
use App\Flows\Laravel\Flow as LaravelFlow;
use PHPUnit\Framework\TestCase;
use App\Flows\Factory as FlowFactory;

class FlowFactoryTest extends TestCase
{

    /** @test */
    public function it_can_instantiate_a_laravel_flow()
    {
        $this->assertInstanceOf(
            LaravelFlow::class, 
            FlowFactory::create(ProjectType::fromString('laravel'))
        );
    }

}
