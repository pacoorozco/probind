<?php

namespace App\Providers;

use App\Services\Pusher\NoOpPusher;
use App\Services\Registry\PusherRegistry;
use Illuminate\Support\ServiceProvider;

class PusherServiceProvider extends ServiceProvider
{
    function register()
    {
        $this->app->singleton(PusherRegistry::class);
    }

    function boot()
    {
        $this->app->make(PusherRegistry::class)->register("noop", new NoOpPusher());
        // $this->app->make(PusherRegistry::class)->register("sftp", new SFTPPusher());
    }
}
