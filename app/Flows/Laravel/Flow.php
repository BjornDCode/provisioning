<?php

namespace App\Flows\Laravel;

use App\Flows\Flow as BaseFlow;
use App\Models\Pipeline\Pipeline;
use App\Steps\Shared\GitProvider;
use App\Steps\Shared\ChooseRepository;
use App\Steps\Shared\GithubAuthentication;
use App\Steps\Shared\NewOrExistingRepository;

class Flow extends BaseFlow
{

    public function __construct(
        public Pipeline $pipeline
    ) {}


    public function steps(): array
    {
        return [
            NewOrExistingRepository::class,
            GitProvider::class,
            GithubAuthentication::class,
            ChooseRepository::class,
        ];
    }

}
