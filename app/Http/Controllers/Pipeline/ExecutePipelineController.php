<?php

namespace App\Http\Controllers\Pipeline;

use App\Enums\StepType;
use Illuminate\Http\Request;
use App\Jobs\ExecutePipeline;
use App\Models\Pipeline\Pipeline;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Flows\Factory as FlowFactory;
use Illuminate\Support\Facades\Redirect;
use App\Jobs\ExecuteCreateRepositoryStep;

class ExecutePipelineController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Pipeline $pipeline)
    {
        $this->authorize('update', $pipeline);

        $flow = FlowFactory::create($pipeline);

        if (!$flow->finished()) {
            return Redirect::route('steps.configuration.render', [
                'pipeline' => $pipeline->id,
                'step' => $flow->next()->type(),
            ]);
        }


        $jobs = $pipeline->steps->map(function ($step) use ($pipeline) {
            return match($step->type) {
                StepType::GITHUB_AUTHENTICATION => new ExecuteCreateRepositoryStep($pipeline),
            };
        })->toArray();

        $jobs = array_merge(
            [
                new ExecutePipeline,
            ],
            $jobs
        );

        Bus::chain($jobs)->dispatch($pipeline);

        return Redirect::route('pipelines.show', [
            'pipeline' => $pipeline->id,
        ])->with('message', 'Pipeline has been started.');
    }

}
