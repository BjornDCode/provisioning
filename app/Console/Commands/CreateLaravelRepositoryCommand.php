<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Account;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use App\Support\LaravelRepositoryCreator;
use GrahamCampbell\GitHub\Facades\GitHub;

class CreateLaravelRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:create {--project=} {--account=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new Laravel repository.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(LaravelRepositoryCreator $service)
    {
        $projectId = $this->option('project');
        $accountId = $this->option('account');

        if (is_null($projectId)) {
            $this->error('Project is required.');
            return 1;
        }

        if (is_null($accountId)) {
            $this->error('Account is required.');
            return 1;
        }

        $project = Project::find($projectId);
        $account = Account::find($accountId);

        try {
            $service->execute($project, $account);
            return 0;
        } catch (Exception $e) {
            return 1;
        }
    }
}
