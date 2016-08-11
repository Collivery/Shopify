<?php
    namespace App\Auth;

    trait CreatesColliveryUserProvider
    {
        protected function createEloquentProvider($config)
        {
            return new ColliveryUserProvider($this->app['hash'], $config['model']);
        }
    }
