<?php

namespace Tests\Feature;

use Tests\TestCase;
use Inertia\Testing\Assert;
use App\Models\Pipeline\Pipeline;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListPipelinesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_cannot_list_pipelines()
    {
        $this->withExceptionHandling();

        // Given
        // When
        $response = $this->get(route('pipelines.index'));

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_list_their_teams_pipelines()
    {
        // Given
        $user = $this->registerNewUser();
        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        Pipeline::factory()->create();

        // When
        $response = $this->get(route('pipelines.index'));

        // Then
        $response->assertInertia(function (Assert $page) use ($pipeline) {
            $page->is('Pipeline/Index');

            $page->where('pending.0.id', $pipeline->id);
        });
    }

    /** @test */
    public function pipelines_are_grouped_by_status()
    {
            // $page->has('pending', 1);
        $this->markTestIncomplete();
    }

}
