<?php

namespace Tests\Feature\Commands;

use Mockery;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Clients\Github\ApiClient;
use App\Clients\Github\TestApiClient;
use Illuminate\Support\Facades\Storage;
use App\Support\LaravelRepositoryCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLaravelRepositoryCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pipeline_is_required()
    {
        // Given
        // When
        $command = $this->artisan('laravel:create');

        // Then 
        $command
            ->expectsOutput('Pipeline is required.')
            ->assertExitCode(1);
    }

    /** @test */
    public function account_is_required()
    {
        // Given
        $pipeline = Pipeline::factory()->create([
            'name' => 'Test',
        ]);

        // When
        $command = $this->artisan("laravel:create", [
            '--pipeline' => $pipeline->id,
        ]);

        // Then 
        $command
            ->expectsOutput('Account is required.')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_executes_the_service()
    {
        $pipeline = Pipeline::factory()->create([
            'name' => 'Test',
        ]);
        $account = Account::factory()->create();
        $this->mock(
            LaravelRepositoryCreator::class,
            function (MockInterface $mock) use ($pipeline, $account) {
                $mock->shouldReceive('execute')
                    ->withArgs(function ($givenPipeline, $givenAccount) use ($pipeline, $account) {
                        return ($givenPipeline->id === $pipeline->id) &&
                            ($givenAccount->id === $account->id);
                    })
                    ->once();
            }
        );

        // When
        $command = $this->artisan("laravel:create", [
            '--pipeline' => $pipeline->id,
            '--account' => $account->id,
        ]);

        // Then
        $command
            ->assertExitCode(0);
    }

}
