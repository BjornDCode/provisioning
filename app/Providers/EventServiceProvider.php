<?php

namespace App\Providers;

use App\Events\PipelineStepFailed;
use App\Listeners\FailPipelineStep;
use Illuminate\Support\Facades\Event;
use App\Listeners\CancelPipelineSteps;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\Events\JobFailed;
use App\Listeners\CreateTeamForNewUser;
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
        JobFailed::class => [
            HandlePipelineExecutionError::class,
        ],
        PipelineStepFailed::class => [
            FailPipelineStep::class,
            CancelPipelineSteps::class,
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
