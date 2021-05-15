<?php

namespace App\Jobs;

use Exception;
use Throwable;
use App\Enums\StepType;
use Illuminate\Support\Str;
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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Clients\Forge\ApiClient as ForgeApiClient;

class ExecuteCreateServerStep implements ShouldQueue
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
    public function __construct($pipeline, $step)
    {
        $this->pipeline = $pipeline;
        $this->step = $step;

        $authenticationConfig = $pipeline->getConfig(
            StepType::fromString(
                StepType::FORGE_AUTHENTICATION
            ),
        );
        $this->account = Account::find($authenticationConfig->details['account_id']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ForgeApiClient $client)
    {
        PipelineStepRunning::dispatch($this->pipeline, $this->step);

        $serverProviderConfig = $this->pipeline->getConfig(
            StepType::fromString(
                StepType::FORGE_SERVER_PROVIDER
            )
        );

        $name = Str::slug($this->pipeline->name . $this->step->meta['environment']);

        $server =  $client->authenticate($this->account)->createServer([
            'name' => $name,
            'type' => 'app',
            'provider' => $serverProviderConfig->details['provider'],
            'region' => $this->step->config->details['region'],
            'size' => $this->step->config->details['size'],
            'php_version' => 'php80',
            'credential_id' => $serverProviderConfig->details['credentials_id'],
        ]);

        $ready = false;

        // 1. Test that the loop is working
        // 2. Test that the init call works
        // 3. Add a fetch call to the loop

        while (!$ready) {
            sleep(120);

            $server =  $client->authenticate($this->account)->fetchServer($server->id);

            $ready = $server->isReady;
        }


        PipelineStepSuccessful::dispatch($this->pipeline, $this->step);
    }

    public function failed(Throwable $exception)
    {
        PipelineStepFailed::dispatch($this->pipeline, $this->step);
    }

}
