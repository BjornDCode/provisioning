<?php

namespace Tests\Integration;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Billing\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_whether_its_active()
    {
        // Given
        $active = Plan::factory()->create([
            'expires_at' => null,
        ]);
        $expiring = Plan::factory()->create([
            'expires_at' => Carbon::now()->addWeeks(2),
        ]);
        $expired = Plan::factory()->create([
            'expires_at' => Carbon::now()->subWeeks(2),
        ]);

        // When
        // Then
        $this->assertTrue($active->active);
        $this->assertTrue($expiring->active);
        $this->assertFalse($expired->active);
    }    

}
