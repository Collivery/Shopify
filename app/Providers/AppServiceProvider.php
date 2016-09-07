<?php

namespace App\Providers;

use App\Helper\Resolver;
use App\Soap\ColliverySoap;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(DebugbarServiceProvider::class);
        }

        $this->app->singleton('soap', function ($app) {
            return new ColliverySoap();
        });

        $this->app->singleton('resolver', function ($app) {
            return new Resolver(app('soap'));
        });
    }
}
