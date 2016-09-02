<?php

namespace App\Http\Controllers;

use App\Collivery\ShopifyClient;
use App\Model\Shop;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected function getShopifyClient(Shop $shop)
    {
        return new ShopifyClient(
            $shop->shop,
            $shop->access_token,
            config('shopify.api_key'),
            config('shopify.secret')
        );
    }
}
