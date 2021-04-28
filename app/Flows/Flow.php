<?php 

namespace App\Flows;

use App\Flows\Step;

abstract class Flow
{

    abstract public function steps(): array;

    public function next(): Step
    {
        $class = collect($this->steps())->first(function ($step) {
            return ! (new $step)->completed();
        });

        return new $class;
    }

}
