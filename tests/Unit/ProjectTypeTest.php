<?php

namespace Tests\Unit;

use App\Enums\ProjectType;
use PHPUnit\Framework\TestCase;

class ProjectTypeTest extends TestCase
{
    /** @test */
    public function it_can_be_laravel()
    {
        $this->assertEquals('laravel', ProjectType::LARAVEL);
    }

    /** @test */
    public function it_can_return_all_types()
    {
        $this->assertEquals([
            'laravel',
        ], ProjectType::all());
    }
}
