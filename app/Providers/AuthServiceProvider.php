<?php

    namespace App\Providers;

    use App\Auth\ColliveryAuthManager;
    use Illuminate\Contracts\Auth\Access\Gate as GateContract;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

    class AuthServiceProvider extends ServiceProvider
    {
        /**
         * The policy mappings for the application.
         *
         * @var array
         */
        protected $policies = [
            'App\Model' => 'App\Policies\ModelPolicy',
        ];

        /**
         * Register any application authentication / authorization services.
         *
         * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
         *
         * @return void
         */
        public function boot(GateContract $gate)
        {
            $this->registerPolicies($gate);
            //
        }

        public function register()
        {
            $this->registerAuthenticator();
        }

        protected function registerAuthenticator()
        {
            $this->app->bind('auth', function ($app) {

                $app['auth.loaded'] = true;

                return new ColliveryAuthManager($app);
            });

            $this->app->bind('auth.driver', function ($app) {
                return $app['auth']->guard();
            });
        }
    }
