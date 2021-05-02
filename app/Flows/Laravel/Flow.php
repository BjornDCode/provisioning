<?php

namespace App\Flows\Laravel;

use App\Flows\Flow as BaseFlow;
use App\Steps\Shared\GitProvider;
use App\Steps\Shared\GithubAuthentication;

class Flow extends BaseFlow
{

    public function steps(): array
    {
        return [
            GitProvider::class,
            GithubAuthentication::class,
        ];
    }

}
