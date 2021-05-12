<?php

namespace App\Providers;

use App\Models\Pipeline\Pipeline;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        Broadcast::channel('pipeline.{pipelineId}', function ($user, $pipelineId) {
            $pipeline = Pipeline::findOrFail($pipelineId);
            return $user->currentTeam->id === $pipeline->team->id;
        });

        require base_path('routes/channels.php');
    }
}
