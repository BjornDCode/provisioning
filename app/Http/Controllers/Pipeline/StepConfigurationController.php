<?php

namespace App\Http\Controllers\Pipeline;

use Inertia\Inertia;
use App\Enums\StepType;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\StepConfiguration;
use App\Http\Controllers\Controller;
use App\Flows\Factory as FlowFactory;
use App\Steps\Factory as StepFactory;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\StepConfigurationResource;

class StepConfigurationController extends Controller
{
    
    public function render(Project $project, $type)
    {
        $this->authorize('update', $project);

        $configuration = $project->configs()->where('type', $type)->first();

        $flow = FlowFactory::create($project);
        $step = StepFactory::create(
            StepType::fromString($type),
            $flow,
        );

        return Inertia::render("Pipeline/Steps/{$step->component()}", [
            'configuration' => !is_null($configuration) 
                ? new StepConfigurationResource($configuration) 
                : null
        ]);
    }

    public function configure(Request $request, Project $project, $type)
    {
        $this->authorize('update', $project);

        $flow = FlowFactory::create($project);
        $step = StepFactory::create(
            StepType::fromString($type),
            $flow,
        );

        $request->validate(
            $step->validationRules()
        );

        StepConfiguration::updateOrCreate([
            'project_id' => $project->id,
            'type' => $type,
        ], [
            'details' => $request->input(),
        ]);


        return Redirect::route('steps.configuration.render', [
            'project' => $project->id,
            'step' => $flow->next()->type(),
        ]);
    }

}
