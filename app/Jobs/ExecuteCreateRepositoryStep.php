<?php

namespace App\Jobs;

use Exception;
use Throwable;
use App\Enums\StepType;
use App\Models\Pipeline\Step;
use Illuminate\Bus\Queueable;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Events\PipelineStepFailed;
use App\Events\PipelineStepRunning;
use App\Events\PipelineStepSuccessful;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use App\Support\LaravelRepositoryCreator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ExecuteCreateRepositoryStep implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public Pipeline $pipeline;
    public Step $step;
    public Account $account;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pipeline)
    {
        $config = $pipeline->getConfig(
            StepType::fromString(
                StepType::GITHUB_AUTHENTICATION
            )
        );

        $this->pipeline = $pipeline;
        $this->step = $config->steps()->first();
        $this->account = Account::find($config->details['account_id']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LaravelRepositoryCreator $service)
    {
        PipelineStepRunning::dispatch($this->pipeline, $this->step);

        $service->execute($this->pipeline, $this->account);

        PipelineStepSuccessful::dispatch($this->pipeline, $this->step);
    }

    public function failed(Throwable $exception)
    {
        PipelineStepFailed::dispatch($this->pipeline, $this->step);
    }

}
