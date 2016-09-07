<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
