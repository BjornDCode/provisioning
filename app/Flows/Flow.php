<?php 

namespace App\Flows;

use App\Steps\Step;

abstract class Flow
{

    abstract public function steps(): array;

    public function next(): Step
    {
        $class = collect($this->steps())->first(function ($step) {
            return ! (new $step($this))->completed();
        });

        return new $class($this);
    }

}
