<?php

namespace Tests\Feature\Flows;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaravelFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_git_provider_step()
    {
        // Render flow url
        // Assert it redirects to the git provider step first

        $this->markTestIncomplete();
    }

    /** @test */
    public function it_redirects_to_the_github_authenication_step()
    {
        $this->markTestIncomplete();
    }

}
