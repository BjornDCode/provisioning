<?php

namespace App\Providers;

use App\Payments\PaymentGateway;
use App\Payments\StripePaymentGateway;
use Illuminate\Support\ServiceProvider;
use App\Support\LaravelRepositoryCreator;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Clients\Forge\ApiClient as ForgeApiClient;
use App\Clients\Github\ApiClient as GithubApiClient;
use App\Clients\Forge\ProductionApiClient as ForgeProductionApiClient;
use App\Clients\Github\ProductionApiClient as GithubProductionApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GithubApiClient::class, GithubProductionApiClient::class);
        $this->app->bind(ForgeApiClient::class, ForgeProductionApiClient::class);
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
        $this->app->bind(LaravelRepositoryCreator::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}
