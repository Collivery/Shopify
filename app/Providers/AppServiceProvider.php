<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mds\Collivery;
use App\Helper\Resolver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
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
