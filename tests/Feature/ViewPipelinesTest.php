<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pipeline;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewPipelinesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_authenticated_users_see_pipelines()
    {
        $this->withExceptionHandling();

        // Given
        // When
        $response = $this->get(route('pipelines.index'));

        // Then
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_view_their_teams_pipelines()
    {
        // Given
        $user = $this->registerNewUser();

        $pipeline = Pipeline::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        // When
        $response = $this->get(route('pipelines.index'));


        // Then
        $response->assertInertia(function (Assert $page) use ($pipeline) {
            $page->is('Pipeline/Index');

            $page->has('pipelines', 1);
            $page->has('pipelines', function (Assert $pipelines) use ($pipeline) {
                $pipelines->where('0.id', $pipeline->id);
            });
        });
    }

}
