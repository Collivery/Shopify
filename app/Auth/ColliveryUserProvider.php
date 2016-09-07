<?php

namespace App\Auth;

use App\Model\User;
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
            //try and find them by email
            if (app('soap')->verify($credentials['email'], $credentials['password'])) {
                $user = new User([
                    'email' => $credentials['email'],
                    'password' => $credentials['password'],
                ]);

                $user->save();
            }
        }

        return $user;
    }
}
