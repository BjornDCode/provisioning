<?php

namespace App\Flows\Shared;

use App\Flows\Step;

class GitProvider implements Step
{

    public function completed(): bool
    {
        return false;
    }

}
