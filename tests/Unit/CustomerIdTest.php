<?php

namespace Tests\Unit;

use App\CustomerId;
use Tests\TestCase;
use App\RequestState;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CustomerIdTest extends TestCase
{

    /** @test */
    public function it_can_create_from_string()
    {
        $this->assertInstanceOf(
            CustomerId::class, 
            CustomerId::fromString(Str::random(6))
        );
    }

    /** @test */
    public function it_can_return_to_string()
    {
        $id = Str::random(6);
        $customerId = CustomerId::fromString($id);
        
        $this->assertEquals($id, $customerId->toString());
    }

}
