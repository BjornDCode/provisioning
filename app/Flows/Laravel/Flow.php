<?php

namespace App\Flows\Laravel;

use App\Flows\Flow as BaseFlow;
use App\Models\Pipeline\Pipeline;
use App\Steps\Shared\GitProvider;
use App\Steps\Laravel\Environments;
use App\Steps\Laravel\HostingPrompt;
use App\Steps\Shared\ChooseRepository;
use App\Steps\Laravel\ForgeAuthentication;
use App\Steps\Laravel\ForgeServerProvider;
use App\Steps\Laravel\ServerConfiguration;
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
            HostingPrompt::class,
            Environments::class,
            ForgeAuthentication::class,
            ForgeServerProvider::class,
            ServerConfiguration::class,
        ];
    }

}
