<?php
return [
    'api_key'           => env('SHOPIFY_APP_API_KEY'),
    'secret'            => env('SHOPIFY_APP_SECRET'),
    'redirect_uri'      => env('SHOPIFY_APP_REDIRECT_URI'),
    'callback_uri'      => env('SHOPIFY_APP_CALLBACK_URI'),
    'domain'            => 'myshopify.com',
    'url'               => 'https://myshopify.com',
    'scopes'            => 'read_customers,write_customers,read_orders,write_orders,read_script_tags,write_script_tags,read_fulfillments,write_fulfillments,read_shipping,write_shipping',
    'app_name'          => 'MDS Collivery Shipping',
    'shipping_endpoint' => env('APP_URL') . '/shipping/rates',
];
