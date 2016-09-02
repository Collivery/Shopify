<?php

namespace App\Http\Controllers\Hooks;

use App\Helper\Resolver;
use App\Http\Controllers\Controller;
use App\Model\Shop;
use App\Model\User;
use App\Soap\ColliverySoap;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * @var Resolver
     */
    private $resolver;

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }
    public function rates(Request $request)
    {
        $origTownId = $this->resolver->getTownId($request->input('rate.origin.city'));

        if (!$origTownId) {
            abort(400, 'Invalid request');
        }

        $destTownId = $this->resolver->getTownId($request->input('rate.destination.city'));

        if (!$destTownId) {
            abort(400, 'Invalid request');
        }

        $parcels = [];

        $items = $request->input('rate.items');

        foreach ($items as $key => $item) {
            $parcels[] = [
                'quantity' => $item['quantity'],
                'weight' => floatval($item['grams']) / 1000,
            ];
        }

        $quoteParams = [
            'from_town_id' => $origTownId,
            'to_town_id' => $destTownId,
            'to_location_type' => 16,
            'collivery_type' => 2,
            'exclude_weekend' => 1,
            'parcels' => $parcels,
        ];

        $services = app('soap')->getServices();
        $rates = [];

        foreach ($services as $key => $service) {
            $quoteParams['service'] = $key;

            $price = app('soap')->getPrice($quoteParams);

            $deliveryTime = Carbon::createFromTimestamp($price['collection_time'],
                'Africa/Johannesburg')->format('Y-m-d G:i:s O');
            $rates[] = [
                'service_name' => $service,
                'service_code' => $key,
                'total_price' => $price['price']['inc_vat'] * 100,
                'currency' => $request->input('rate.currency'),
                'min_delivery_date' => $deliveryTime,
                'max_delivery_date' => $deliveryTime,
            ];
        }

        return response()->json(['rates' => $rates]);
    }

    public function customersCreate(Request $request)
    {
    }

    public function customersUpdate(Request $request)
    {
    }

    public function ordersPaid(Request $request)
    {
        $shop = Shop::installed()->byName($request->header('X-Shopify-Shop-Domain'))->first();

        if (!$shop) {
            abort('404', 'Requested resource was not found');
        }

        $user = User::find($shop->user_id)->first();

        $colliveryClient = new ColliverySoap([
            'user_email' => $user->email,
            'user_password' => $user->password,
        ]);

        if (!$colliveryClient->verify($user->email, $user->password)) {
            abort(500, 'Internal server error');
        }

        $shopInfo = $this->getShopInfo($shop);

        if (!$shopInfo) {
            abort(400, 'Bad request sent');
        }

        $shopName = $shopInfo['name'];
        $shopEmail = $shopInfo['email'];
        $shopPhone = $shopInfo['phone'];
        $shopZip = $shopInfo['zip'];

        $customerPhone = $request->input('shipping_address.phone');
        $service = $request->input('shipping_lines.0.code');

        $srcTown = $request->input('line_items.0.origin_location.city');
        $srcSuburb = $request->input('line_items.0.origin_location.address2');
        $srcName = $request->input('line_items.0.origin_location.name');
        $srcStreetAddress = $request->input('line_items.0.origin_location.address1');

        $destTown = $request->input('line_items.0.destination_location.city');
        $destSuburb = $request->input('shipping_address.address2');
        $destName = $request->input('shipping_address.name').' '.$request->input('shipping_address.last_name');
        $destStreetAddress = $request->input('line_items.0.destination_location.address1');

        $srcTownId = app('resolver')->getTownId($srcTown);
        $srcSuburbId = app('resolver')->getSuburbId($srcSuburb, $srcTownId);

        $destTownId = app('resolver')->getTownId($destTown);
        $destSuburbId = app('resolver')->getSuburbId($destSuburb, $destTownId);

        if (!$srcSuburbId || !$srcTownId || !$destSuburbId || !$destTownId) {
            abort(400, 'Bad request');
        }

        $srcAddress = $colliveryClient->addAddress([
            'company_name' => $srcName,
            'street' => $srcStreetAddress,
            'location_type' => 16,
            'suburb_id' => $srcSuburbId,
            'town_id' => $srcTownId,
            'full_name' => $srcName,
            'phone' => $shopPhone,
            'zip' => $shopZip,
        ]);

        if (!$srcAddress) {
            abort(400, 'Bad request sent. Please fix you address');
        }

        $destAddress = $colliveryClient->addAddress([
            'company_name' => $destName,
            'street' => $destStreetAddress,
            'location_type' => 16,
            'suburb_id' => $destSuburbId,
            'town_id' => $destTownId,
            'full_name' => $destName,
            'phone' => $customerPhone,
        ]);

        if (!$destAddress) {
            abort(400, 'Bad request sent. Please fix you addresses');
        }

        $items = $request->input('line_items');

        $collivery = [
            'collivery_from' => $srcAddress['address_id'],
            'contact_from' => $srcAddress['contact_id'],
            'collivery_to' => $destAddress['address_id'],
            'contact_to' => $destAddress['contact_id'],
            'collivery_type' => 2,
            'service' => $service,
        ];

        $parcels = [];

        foreach ($items as $key => $item) {
            $parcels = [
                'weight' => $item['grams'] / 1000,
                'quantity' => $item['quantity'],
            ];
        }

        $collivery['parcels'] = $parcels;

        $collivery = $colliveryClient->validate($collivery);

        if (!$collivery) {
            abort(400, 'Bad request please verify your data');
        }

        $colliveryId = $colliveryClient->addCollivery($collivery);

        $result = $colliveryClient->acceptCollivery($colliveryId);

        return $request->input();
    }

    private function getShopInfo(Shop $shop)
    {
        $client = $this->getShopifyClient($shop);

        $info = [];

        try {
            $info = $client->call('GET', '/admin/shop.json');
        } catch (\Exception $e) {
            throw $e;
        }

        return $info;
    }

    public function ordersCreate(Request $request)
    {
    }

    public function appUninstalled(Request $request)
    {
    }

    public function shopUpdate(Request $request)
    {
    }
}
