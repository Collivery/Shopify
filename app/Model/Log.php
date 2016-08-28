<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function shop()
    {
        return $this->belongsTo('App\Model\Shop');
    }
}
