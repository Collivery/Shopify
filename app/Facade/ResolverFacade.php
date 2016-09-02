<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class ResolverFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'resolver';
    }
}
