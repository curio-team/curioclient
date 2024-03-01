<?php

namespace Curio\SdClient;

use Illuminate\Support\ServiceProvider;

class SdClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if (config('sdclient.use_migration') == 'yes') {
            $this->loadMigrationsFrom(__DIR__.'/migrations');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/sdclient.php', 'sdclient'
        );

        $this->app->make('Curio\SdClient\SdClientController');
        $this->app->singleton('Curio\SdApi', function () {
            return new SdApi();
        });
    }
}
