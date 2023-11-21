<?php

namespace App\Providers;

use App\Services\ContractApiManager;
use App\Services\ContractApiManagerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContractApiManagerInterface::class, function () {
            return new ContractApiManager(
                config('services.jag.api_url'),
                config('services.jag.collection_name')
            );
        });
    }
}
