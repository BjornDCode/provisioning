<?php

namespace Tests\Unit;

use App\SubscriptionId;
use Tests\TestCase;
use App\RequestState;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubscriptionIdTest extends TestCase
{

    /** @test */
    public function it_can_create_from_string()
    {
        $this->assertInstanceOf(
            SubscriptionId::class, 
            SubscriptionId::fromString(Str::random(6))
        );
    }

    /** @test */
    public function it_can_return_to_string()
    {
        $id = Str::random(6);
        $subscriptionId = SubscriptionId::fromString($id);
        
        $this->assertEquals($id, $subscriptionId->toString());
    }

}
