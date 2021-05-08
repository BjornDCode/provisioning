<?php

namespace App\Support;

use Illuminate\Support\Str;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Clients\Github\ApiClient;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class LaravelRepositoryCreator
{

    public function execute(Pipeline $pipeline, Account $account)
    {
        $githubClient = app()->make(ApiClient::class);
        
        // Ensure directory exists
        if (!Storage::exists("repositories/{$pipeline->team->id}")) {
            Storage::makeDirectory("repositories/{$pipeline->team->id}");
        }

        // Ensure pipeline folder doesn't exist
        if (Storage::exists("repositories/{$pipeline->team->id}/{$pipeline->name}")) {
            Storage::deleteDirectory("repositories/{$pipeline->team->id}/{$pipeline->name}");
        }

        // Create pipeline directory
        Storage::makeDirectory("repositories/{$pipeline->team->id}/{$pipeline->name}");

        // Move the script into the pipeline directory
        chdir("storage/app/repositories/{$pipeline->team->id}/{$pipeline->name}");

        // Scaffold pipeline with composer
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
        $response = $githubClient->authenticate($account)->createRepository($pipeline->name);
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
