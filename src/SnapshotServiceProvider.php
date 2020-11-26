<?php

namespace SebRave\Snapshot;

use Illuminate\Support\ServiceProvider;

class SnapshotServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('snapshot', function($app) {
            return new Snapshot();
        });

        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'snapshot');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'snapshot');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/config/config.php' => config_path('snapshot.php'),
            ], 'config');

        }
    }
}