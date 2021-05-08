<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\RequestState;
use Illuminate\Http\Request;

class RequestStateTest extends TestCase
{

    /** @test */
    public function it_can_encode_state()
    {
        $state = [
            'redirect' => 'https://google.com',
        ];

        $encoded = urlencode(
            base64_encode(
                json_encode([
                    'redirect' => 'https://google.com',
                ])
            )
        );

        $this->assertEquals(
            $encoded,
            RequestState::fromArray($state)
        );
    }

    /** @test */
    public function it_can_decode_state()
    {
        $state = [
            'redirect' => 'https://google.com',
        ];

        $encoded = urlencode(
            base64_encode(
                json_encode([
                    'redirect' => 'https://google.com',
                ])
            )
        );

        $this->assertEquals(
            $state,
            RequestState::fromString($encoded)
        );
    }

}
