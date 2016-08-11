<?php

    namespace App\Auth;

    use Illuminate\Auth\EloquentUserProvider;
    use Illuminate\Contracts\Hashing\Hasher;

    class ColliveryUserProvider extends EloquentUserProvider
    {
        public function __construct(Hasher $contract, $model)
        {
            parent::__construct($contract, $model);
        }

        public function retrieveByCredentials(array $credentials)
        {
            $user = parent::retrieveByCredentials($credentials);

            if (!$user) {
                //do some collivery calls
            }

            return $user;
        }
    }
