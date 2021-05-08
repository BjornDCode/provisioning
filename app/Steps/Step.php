<?php 

namespace App\Steps;

interface Step
{

    public function completed(): bool;

    public function type(): string;

    public function component(): string;

    public function validationRules(): array;

    public function context(): array;

}
