<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Order
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order byId($id)
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int $shop_id
 * @property int $shopify_order_id
 * @property string $order_status_url
 * @property string $waybill_number
 * @property string $order_number
 * @property int $status
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereShopId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereShopifyOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereOrderStatusUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereWaybillNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Order whereUpdatedAt($value)
 */
class Order extends Model
{
    public function scopeById($query, $id)
    {
        return $query->where('shopify_order_id', $id);
    }
}
