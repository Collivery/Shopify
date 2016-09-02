<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function scopeById($query, $id)
    {
        return $query->where('shopify_order_id', $id);
    }
}
