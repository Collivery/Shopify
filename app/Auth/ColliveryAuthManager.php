<?php

namespace App\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Application;

class ColliveryAuthManager extends AuthManager
{
    use CreatesColliveryUserProvider;

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}
