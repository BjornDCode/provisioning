<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Account;
use App\Models\Project;
use Mockery\MockInterface;
use App\Clients\Github\ApiClient;
use App\Clients\Github\TestApiClient;
use Illuminate\Support\Facades\Storage;
use App\Support\LaravelRepositoryCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLaravelRepositoryCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function project_is_required()
    {
        // Given
        // When
        $command = $this->artisan('laravel:create');

        // Then 
        $command
            ->expectsOutput('Project is required.')
            ->assertExitCode(1);
    }

    /** @test */
    public function account_is_required()
    {
        // Given
        $project = Project::factory()->create([
            'name' => 'Test',
        ]);

        // When
        $command = $this->artisan("laravel:create", [
            '--project' => $project->id,
        ]);

        // Then 
        $command
            ->expectsOutput('Account is required.')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_executes_the_service()
    {
        $project = Project::factory()->create([
            'name' => 'Test',
        ]);
        $account = Account::factory()->create();
        $this->mock(
            LaravelRepositoryCreator::class,
            function (MockInterface $mock) use ($project, $account) {
                $mock->shouldReceive('execute')
                    ->withArgs(function ($givenProject, $givenAccount) use ($project, $account) {
                        return ($givenProject->id === $project->id) &&
                            ($givenAccount->id === $account->id);
                    })
                    ->once();
            }
        );

        // When
        $command = $this->artisan("laravel:create", [
            '--project' => $project->id,
            '--account' => $account->id,
        ]);

        // Then
        $command
            ->assertExitCode(0);
    }

}
