<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfigureStepsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_configure_a_step()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function an_authenticated_user_cannot_configure_a_step_for_another_teams_project()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function an_authenticated_user_can_create_a_step_configuration()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function an_authenticated_user_can_update_an_existing_configuration()
    {
        $this->markTestIncomplete();
    }

}
