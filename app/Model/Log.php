<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Log
 *
 * @property-read \App\Model\Shop $shop
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $shop_id
 * @property string $ip
 * @property string $headers
 * @property string $payload
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log whereShopId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log whereHeaders($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log wherePayload($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Log whereDeletedAt($value)
 */
class Log extends Model
{
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
