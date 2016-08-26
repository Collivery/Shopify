<?php

namespace App\Providers;

use App\Helper\Resolver;
use Illuminate\Support\ServiceProvider;
use Mds\Collivery;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton('soap', function ($app) {
            return new Collivery();
        });

        $this->app->singleton('resolver', function ($app) {
            return new Resolver(app('soap'));
        });
    }
}
