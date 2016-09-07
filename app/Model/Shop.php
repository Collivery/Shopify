<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Shop
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Log[] $logs
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop installed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop byName($shop)
 * @mixin \Eloquent
 * @property integer $id
 * @property string $shop
 * @property string $name
 * @property string $nonce
 * @property string $access_token
 * @property integer $user_id
 * @property integer $carrier_id
 * @property string $email
 * @property string $province
 * @property string $province_code
 * @property string $country
 * @property string $country_code
 * @property string $zip
 * @property string $city
 * @property string $phone
 * @property string $customer_email
 * @property string $address1
 * @property string $address2
 * @property integer $app_installed
 * @property integer $carrier_installed
 * @property integer $webhooks_installed
 * @property string $carrier_installed_on
 * @property string $carrier_uninstalled_on
 * @property string $webhooks_installed_on
 * @property string $webhooks_uninstalled_on
 * @property string $app_installed_on
 * @property string $app_uninstalled_on
 * @property string $app_updated_on
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereShop($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereNonce($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereProvince($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereProvinceCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCountryCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCustomerEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereAddress1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereAddress2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereAppInstalled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCarrierInstalled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereWebhooksInstalled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCarrierInstalledOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCarrierUninstalledOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereWebhooksInstalledOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereWebhooksUninstalledOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereAppInstalledOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereAppUninstalledOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereAppUpdatedOn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Shop whereDeletedAt($value)
 */
class Shop extends Model
{
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return $this->app_installed === 1;
    }

    /**
     *Shop was installed then uninstalled.
     *
     * @return bool
     */
    public function hasBeenInstalled()
    {
        return $this->app_installed === 2;
    }

    /**
     * @return bool
     */
    public function hasNeverBeenInstalled()
    {
        return $this->app_installed == 0;
    }

    /**
     * Find active shops.
     */
    public function scopeInstalled($query)
    {
        return $query->where('app_installed', 1);
    }

    public function scopeByName($query, $shop)
    {
        return $query->where('shop', $shop);
    }

    public function setInfo(array $data)
    {
        $fillableFields = $this->fillable;

        $this->fillable([
            'name',
            'email',
            'province',
            'province_code',
            'country',
            'country_code',
            'zip',
            'city',
            'phone',
            'customer_email',
            'address1',
            'address2',
        ]);
        $this->fill($data);
        $this->fillable($fillableFields);
    }
}
