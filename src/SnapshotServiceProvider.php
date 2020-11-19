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
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'snapshot');
    }
}