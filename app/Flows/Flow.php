<?php 

namespace App\Flows;

use App\Steps\Step;

abstract class Flow
{

    abstract public function steps(): array;

    public function next(): Step|null
    {
        $step = collect($this->steps())->first(function ($step) {
            return ! (new $step($this))->completed();
        });

        if (is_null($step)) {
            return null;
        }

        return new $step($this);
    }

    public function finished(): bool
    {
        return is_null($this->next());
    }

}
