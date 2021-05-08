<?php

namespace App\Http\Controllers\Pipeline;

use Inertia\Inertia;
use App\Enums\StepType;
use App\Models\Pipeline;
use Illuminate\Http\Request;
use App\Models\StepConfiguration;
use App\Http\Controllers\Controller;
use App\Flows\Factory as FlowFactory;
use App\Steps\Factory as StepFactory;
use App\Http\Resources\PipelineResource;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\StepConfigurationResource;

class StepConfigurationController extends Controller
{
    
    public function render(Pipeline $pipeline, $type)
    {
        $this->authorize('update', $pipeline);

        $configuration = $pipeline->configs()->where('type', $type)->first();

        $flow = FlowFactory::create($pipeline);
        $step = StepFactory::create(
            StepType::fromString($type),
            $flow,
        );

        return Inertia::render(
            "Pipeline/Steps/{$step->component()}", 
            array_merge(
                [
                    'pipeline' => new PipelineResource($pipeline),
                    'configuration' => !is_null($configuration) 
                        ? new StepConfigurationResource($configuration) 
                        : null
                ],
                $step->context(),
            )
    );
    }

    public function configure(Request $request, Pipeline $pipeline, $type)
    {
        $this->authorize('update', $pipeline);

        $flow = FlowFactory::create($pipeline);
        $step = StepFactory::create(
            StepType::fromString($type),
            $flow,
        );

        $request->validate(
            $step->validationRules()
        );

        StepConfiguration::updateOrCreate([
            'pipeline_id' => $pipeline->id,
            'type' => $type,
        ], [
            'details' => $request->input(),
        ]);

        $next = $flow->next();

        if (is_null($next)) {
            return Redirect::route('pipelines.show', [
                'pipeline' => $pipeline->id,
            ]);
        }

        return Redirect::route('steps.configuration.render', [
            'pipeline' => $pipeline->id,
            'step' => $next->type(),
        ]);
    }

}
