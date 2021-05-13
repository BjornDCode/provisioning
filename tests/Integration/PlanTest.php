<?php

namespace Tests\Integration;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Billing\Plan;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_whether_its_on_a_paid_plan()
    {
        // Given
        $free = Plan::factory()->create([
            'plan_id' => Config::get('services.stripe.free_plan_id'),
        ]);
        $paid = Plan::factory()->create([
            'plan_id' => Config::get('services.stripe.paid_plan_id'),
        ]);
        $noPlan = Plan::factory()->create([
            'plan_id' => null,
        ]);

        // When
        // Then
        $this->assertFalse($free->paid);
        $this->assertTrue($paid->paid);
        $this->assertFalse($noPlan->paid);
    }

}
