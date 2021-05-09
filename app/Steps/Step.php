<?php 

namespace App\Steps;

use App\Models\Pipeline\StepConfiguration;

interface Step
{

    public function completed(): bool;

    public function type(): string;

    public function component(): string;

    public function validationRules(): array;

    public function context(): array;

    public function createSteps(StepConfiguration $config): void;

    public function cleanup(StepConfiguration $config): void;

}
