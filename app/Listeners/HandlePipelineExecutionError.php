<?php

namespace App\Listeners;

use App\Events\PipelineStepFailed;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\ExecuteCreateRepositoryStep;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandlePipelineExecutionError
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $jobs = collect([
            ExecuteCreateRepositoryStep::class,
        ]);

        $isPipelineError = $jobs->contains(function ($class) use ($event) {
            return $event->job instanceof $class;
        });

        if ($isPipelineError) {
            PipelineStepFailed::dispatch($event->job->pipeline, $event->job->step);
        }

    }
}
