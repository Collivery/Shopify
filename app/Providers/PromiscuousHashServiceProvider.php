<?php

namespace App\Providers;

use App\Auth\PromiscuousHasher;
use Illuminate\Hashing\HashServiceProvider;

class PromiscuousHashServiceProvider extends HashServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->bind('hash', function () {
            return new PromiscuousHasher();
        });
    }
}
