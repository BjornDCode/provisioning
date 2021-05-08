<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Pipeline\Pipeline;
use App\Flows\Factory as FlowFactory;
use App\Flows\Laravel\Flow as LaravelFlow;

class FlowFactoryTest extends TestCase
{

    /** @test */
    public function it_can_instantiate_a_laravel_flow()
    {
        $pipeline = new Pipeline([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $this->assertInstanceOf(
            LaravelFlow::class, 
            FlowFactory::create($pipeline)
        );
    }

}
