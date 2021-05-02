<?php

namespace App\Steps\Shared;

use App\Steps\Step;

class GitProvider implements Step
{

    public function type(): string
    {
        return 'git-provider';
    }

    public function component(): string
    {
        return 'GitProvider';
    }
    
    public function completed(): bool
    {
        return false;
    }


}
