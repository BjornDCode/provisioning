<?php

namespace Tests\Unit;

use App\Models\Project;
use PHPUnit\Framework\TestCase;
use App\Flows\Factory as FlowFactory;
use App\Flows\Laravel\Flow as LaravelFlow;

class FlowFactoryTest extends TestCase
{

    /** @test */
    public function it_can_instantiate_a_laravel_flow()
    {
        $project = new Project([
            'name' => 'HEL',
            'type' => 'laravel',
        ]);

        $this->assertInstanceOf(
            LaravelFlow::class, 
            FlowFactory::create($project)
        );
    }

}
