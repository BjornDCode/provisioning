<?php

namespace App\Support;

use App\Models\Account;
use App\Models\Project;
use Illuminate\Support\Str;
use App\Clients\Github\ApiClient;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class LaravelRepositoryCreator
{

    public function execute(Project $project, Account $account)
    {
        $githubClient = app()->make(ApiClient::class);
        
        // Ensure directory exists
        if (!Storage::exists("repositories/{$project->team->id}")) {
            Storage::makeDirectory("repositories/{$project->team->id}");
        }

        // Ensure project folder doesn't exist
        if (Storage::exists("repositories/{$project->team->id}/{$project->name}")) {
            Storage::deleteDirectory("repositories/{$project->team->id}/{$project->name}");
        }

        // Create project directory
        Storage::makeDirectory("repositories/{$project->team->id}/{$project->name}");

        // Move the script into the project directory
        chdir("storage/app/repositories/{$project->team->id}/{$project->name}");

        // Scaffold project with composer
        $process = new Process([
            'composer', 
            'create-project',
            'laravel/laravel',
            '.',
            '--remove-vcs',
            '--prefer-dist',
            '--no-install',
        ]);
        $process->run();

        // Initialise .git repository
        $process = new Process([
            'git',
            'init',
            '-q',
        ]);
        $process->run();

        // Stage all files
        $process = new Process([
            'git',
            'add',
            '.',
        ]);
        $process->run();

        // Configure commiter
        $process = new Process([
            'git',
            'config',
            'user.email',
            '"' . $account->email . '"'
        ]);
        $process->run();

        $process = new Process([
            'git',
            'config',
            'user.name',
            '"' . $account->identifier . '"'
        ]);
        $process->run();

        // Commit files
        $process = new Process([
            'git',
            'commit',
            '-q',
            '-m',
            "Initialise with Provisioning",
        ]);
        $process->run();

        // Set main branch
        $process = new Process([
            'git',
            'branch',
            '-M',
            'main',
        ]);
        $process->run();


        // Create repository on GitHub
        $response = $githubClient->authenticate($account)->createRepository($project->name);
        $gitUrl = $response->collect()->get('clone_url');

        // Set remote
        $process = new Process([
            'git',
            'remote',
            'add',
            'origin',
            $gitUrl,
        ]);
        $process->run();

        // Push code
        $url = 'https://' . $account->identifier . ':' . $account->token . '@' . Str::after($gitUrl, 'https://');
        $process = new Process([
            'git',
            'push',
            '-q',
            '-u',
            $url,
            'main',
        ]);
        $process->run();
    }

}
