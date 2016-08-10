<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public function logs()
    {
        return $this->hasMany('App\Log');
    }
}
