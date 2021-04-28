<?php

namespace App\Flows\Laravel;

use App\Flows\Flow as BaseFlow;
use App\Flows\Shared\GitProvider;

class Flow extends BaseFlow
{

    public function steps(): array
    {
        return [
            GitProvider::class,
        ];
    }

}
