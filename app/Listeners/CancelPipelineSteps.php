<?php

namespace App\Listeners;

use App\Enums\PipelineStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelPipelineSteps
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $steps = $event->pipeline->steps->filter(fn ($step) => $step->status === PipelineStatus::PENDING);

        $steps->each(function ($step) {
            $step->update([
                'status' => PipelineStatus::CANCELLED,
            ]);
        });
    }
}
