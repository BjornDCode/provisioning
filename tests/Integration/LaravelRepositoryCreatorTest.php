<?php 

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\Account;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use App\Support\LaravelRepositoryCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaravelRepositoryCreatorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        collect(Storage::allDirectories("repositories"))->each(function ($directory) {
            Storage::deleteDirectory($directory);
        });
    }

    protected function tearDown(): void
    {
        collect(Storage::allDirectories("repositories"))->each(function ($directory) {
            Storage::deleteDirectory($directory);
        });

        parent::tearDown();
    }

    /** @test */
    public function it_creates_a_laravel_app()
    {
        $this->app->bind(ApiClient::class, TestApiClient::class);

        // Given
        $service = new LaravelRepositoryCreator();
        $project = Project::factory()->create([
            'name' => 'Test',
        ]);
        $account = Account::factory()->create();

        // When
        $service->execute($project, $account);

        // Then 
        $this->assertTrue(
            Storage::exists("repositories/{$project->team->id}/{$project->name}/.env")
        );
    }

    /** @test */
    public function it_initialises_a_git_repository()
    {
        $this->app->bind(ApiClient::class, TestApiClient::class);

        // Given
        $service = new LaravelRepositoryCreator();
        $project = Project::factory()->create([
            'name' => 'Test',
        ]);
        $account = Account::factory()->create();

        // When
        $service->execute($project, $account);

        // Then 
        $this->assertTrue(
            Storage::exists("repositories/{$project->team->id}/{$project->name}/.git")
        );
    }

}
