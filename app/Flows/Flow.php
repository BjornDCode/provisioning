<?php 

namespace App\Flows;

use App\Flows\Step;

abstract class Flow
{

    abstract public function steps(): array;

    public function next(): Step
    {
        return collect($this->steps())->first(function ($step) {
            return !$step->completed();
        });
    }

}
