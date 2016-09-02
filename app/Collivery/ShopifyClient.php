<?php

namespace App\Collivery;

class ShopifyClient extends \ShopifyClient
{
    public function setAccessToken($token)
    {
        $this->token = $token;
    }
}
