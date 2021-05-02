<?php

namespace App\Http\Controllers\Pipeline;

use Inertia\Inertia;
use App\Enums\StepType;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Steps\Factory as StepFactory;
use App\Http\Resources\StepConfigurationResource;

class StepController extends Controller
{
    
    public function configure(Project $project, $type)
    {
        $this->authorize('update', $project);

        $configuration = $project->configs()->where('type', $type)->first();

        $step = StepFactory::create(
            StepType::fromString($type)
        );

        return Inertia::render("Pipeline/Steps/{$step->component()}", [
            'configuration' => !is_null($configuration) 
                ? new StepConfigurationResource($configuration) 
                : null
        ]);
    }

}
