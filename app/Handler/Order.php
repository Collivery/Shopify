<?php

namespace App\Handler;

use App\Collivery\ShopifyClient;
use App\Model\Order as ShopifyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mds\ColliveryClient;

class Order
{
    private $order;
    private $shopifyClient;
    private $request;

    private function __construct(ShopifyOrder $order, Request $request, ShopifyClient $client)
    {
        $this->order = $order;
        $this->shopifyClient = $client;
        $this->request = $request;
    }

    public static function create(ShopifyOrder $order, Request $request, ShopifyClient $client)
    {
        return new self($order, $request, $client);
    }

    public function addCollivery(ColliveryClient $client)
    {
    }

    public function fulfill()
    {
        if (!$this->order) {
            return false;
        }

        $fulfillment = [
            'tracking_url' => config('app.url')."/shop/order/tracking/{$this->order->waybill_number}",
            'tracking_company' => config('company.name'),
            'line_items' => [],
        ];

        foreach ($this->request->input('line_items') as $index => $item) {
            $fulfillment['line_items'][] = [
                'id' => $item['id'],
            ];
        }

        try {
            $this->shopifyClient->call('POST', "/admin/orders/{$this->order->shopify_order_id}/fulfillments.json", [
                'fulfillment' => $fulfillment,
            ]);
            $this->order->status = 3;

            return true;
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;

            return false;
        }
    }
}
