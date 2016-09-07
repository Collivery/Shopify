<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class ColliveryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'soap';
    }
}
