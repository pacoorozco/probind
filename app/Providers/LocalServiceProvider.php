<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LocalServiceProvider extends ServiceProvider
{

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers only for 'local' environment
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded only for
    | 'local' environment request to your application.
    |
    */
    protected $providers = [
        'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class', // Laravel IDE helper
    ];

    /*
    |--------------------------------------------------------------------------
    | Class Aliases  only for 'local' environment
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started only for 'local' environment.
    |
    */
    protected $aliases = [
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the service providers for local environment
        if ($this->app->isLocal() && ! empty($this->providers)) {
            foreach ($this->providers as $provider) {
                $this->app->register($provider);
            }
            // Register the alias
            if ( ! empty($this->aliases)) {
                foreach ($this->aliases as $alias => $facade) {
                    $this->app->alias($alias, $facade);
                }
            }
        }
    }
}
