<?php

namespace App\Flows\Shared;

use App\Flows\Step;

class GitProvider implements Step
{

    public function slug(): string
    {
        return 'git-provider';
    }
    
    public function completed(): bool
    {
        return false;
    }

}
