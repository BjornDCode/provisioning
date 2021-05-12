<?php

namespace App\Providers;

use App\Events\PipelineStepFailed;
use App\Events\PipelineStepRunning;
use Illuminate\Support\Facades\Event;
use App\Events\PipelineStepSuccessful;
use App\Listeners\CancelPipelineSteps;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\Events\JobFailed;
use App\Listeners\CreateTeamForNewUser;
use App\Listeners\SetPipelineStepToFailed;
use App\Listeners\SetPipelineStepToRunning;
use App\Listeners\SetPipelineStepToSuccessful;
use App\Listeners\HandlePipelineExecutionError;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            CreateTeamForNewUser::class,
            SendEmailVerificationNotification::class,
        ],
        PipelineStepFailed::class => [
            SetPipelineStepToFailed::class,
            CancelPipelineSteps::class,
        ],
        PipelineStepRunning::class => [
            SetPipelineStepToRunning::class,
        ],
        PipelineStepSuccessful::class => [
            SetPipelineStepToSuccessful::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
