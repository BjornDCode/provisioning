<?php

namespace App\Flows\Laravel;

use App\Models\Project;
use App\Flows\Flow as BaseFlow;
use App\Steps\Shared\GitProvider;
use App\Steps\Shared\GithubAuthentication;

class Flow extends BaseFlow
{

    public function __construct(
        public Project $project
    ) {}


    public function steps(): array
    {
        return [
            GitProvider::class,
            GithubAuthentication::class,
        ];
    }

}
