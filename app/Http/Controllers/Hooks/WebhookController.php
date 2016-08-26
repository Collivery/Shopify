<?php

namespace App\Http\Controllers\Hooks;

use App\Helper\Resolver;
use App\Http\Controllers\Controller;
use App\Shop;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mds\Collivery;

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

    public function ordersCreate(Request $request)
    {
        $shop = Shop::installed()->byName($request->header('X-Shopify-Shop-Domain'))->first();
        if (!$shop) {
            abort('404', 'Request resource was not found');
        }

        $user = User::find($shop->user_id)->first();

        $colliveryClient = new Collivery([
            'user_email' => $user->email,
            'user_password' => $user->password,
        ]);

        if (!$colliveryClient->authenticate()) {
            abort(500, 'Internal server error');
        }

        dd($user);
    }

    public function ordersPaid(Request $request)
    {
    }

    public function appUninstalled(Request $request)
    {
    }

    public function shopUpdate(Request $request)
    {
    }
}
