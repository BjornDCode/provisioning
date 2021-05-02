<?php 

namespace App\Flows;

interface Step
{

    public function completed(): bool;

    public function slug(): string;

}
