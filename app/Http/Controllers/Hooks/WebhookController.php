<?php

namespace App\Http\Controllers\Hooks;

use App\Helper\Resolver;
use App\Exceptions\OrderProcessException;
use App\Handler\Order as OrderHandler;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Shop;
use App\Model\User;
use App\Soap\ColliverySoap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * @var Resolver
     */
    private $resolver;

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
        $this->middleware('validWebhook');
    }

    public function rates(Request $request)
    {
        $origTownId = $this->resolver->getTownId($request->input('rate.origin.city'));

        if (!$origTownId) {
            abort(400, 'Invalid request');
        }

        $destTownId = $this->resolver->getTownId($request->input('rate.destination.city'));

        //shopify is posting an address with no city which tends to be very inaccurate
        //default to one city in a province
        if (!$destTownId) {
            $provinceCode = $request->input('rate.destination.province');
            $provinceMap = config('provinces');
            $province = null;
            foreach ($provinceMap as $k => $v) {
                if ($v['code'] == $provinceCode || $v['alias'] == $provinceCode) {
                    $province = $k;
                }
            }

            if (!$province) {
                abort(400, 'Bad request');
            }

            $destTownId = config("provinces.{$province}.major_town");
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

            $deliveryTime = Carbon::createFromTimestamp(
                $price['collection_time'],
                'Africa/Johannesburg'
            )->format('Y-m-d G:i:s O');

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
            abort(403, 'Order processing failed');
        }

        $order = Order::byId($request->input('id'))->first();

        if (!$order) {
            $order = new Order();
            $order->shop_id = $shop->id;
            $order->order_number = $request->input('number');
            $order->order_status_url = $request->input('order_status_url');
            $order->status = 0;
            $order->shopify_order_id = $request->input('id');
        }

        if (!($order->status === 3)) {
            try {
                $user = User::find($shop->user_id)->first();
                $user->setVisible(['password']);

                $colliveryClient = new ColliverySoap([
                    'user_email' => $user->email,
                    'user_password' => $user->password,
                ]);

                if (!$colliveryClient->verify()) {
                    abort(500, 'Internal server error. Failed to connect');
                }

                $user->setHidden(['password']);

                $shopPhone = $shop->phone;
                $shopZip = $shop->zip;

                $customerPhone = $request->input('shipping_address.phone');
                $service = $request->input('shipping_lines.0.code');

                $srcTown = $shop->city;
                $srcSuburb = $shop->address2;
                $srcName = $shop->name;
                $srcStreetAddress = $shop->address1;

                $destTown = $request->input('shipping_address.city');
                $destSuburb = $request->input('shipping_address.address2');
                $destName = $request->input('shipping_address.name').' '.$request->input('shipping_address.last_name');
                $destStreetAddress = $request->input('shipping_address.address1');

                $srcTownId = $this->resolver->getTownId($srcTown);
                $srcSuburbId = $this->resolver->getSuburbId($srcSuburb, $srcTownId);

                $destTownId = $this->resolver->getTownId($destTown);
                $destSuburbId = $this->resolver->getSuburbId($destSuburb, $destTownId);

                if (!$srcSuburbId || !$srcTownId || !$destSuburbId || !$destTownId) {
                    throw new OrderProcessException('Address could not be resolved');
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
                    throw new OrderProcessException('Address src could not be resolved');
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
                    throw new OrderProcessException('Address dest could not be resolved');
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
                    throw new OrderProcessException('Invalid collivery');
                }

                if ($order->status != 2) {
                    $colliveryId = !$order->status ? $colliveryClient->addCollivery($collivery) : $order->waybill_number;

                    if ($colliveryId) {
                        $order->status = 1;
                        $order->waybill_number = $colliveryId;
                    }

                    $colliveryAccepted = $colliveryClient->acceptCollivery($colliveryId);

                    if ($colliveryAccepted) {
                        $order->status = 2;
                    }
                }

                //fulfill
                if ($order->status === 2) {
                    $orderHandler = OrderHandler::create($order, $request, $this->getShopifyClient($shop));
                    if ($orderHandler->fulfill()) {
                        $order->status = 3;
                    }
                } else {
                    throw new OrderProcessException('Failed to process order');
                }
            } catch (OrderProcessException $e) {
                Log::error(sprintf('Code %d : Line %d:File : %s Message %s', $e->getCode(), $e->getLine(),
                    $e->getFile(), $e->getMessage()));
                abort(400, $e->getMessage());
            } finally {
                if ($order->save()) {
                    Log:info(sprintf('%s saved %s', $order->number, $order->waybill_number));
                }
            }
        }

        return $request->input();
    }

    public function ordersCreate(Request $request)
    {
    }

    public function appUninstalled(Request $request)
    {
    }

    public function shopUpdate(Request $request)
    {
        $shop = Shop::byName($request->header('X-Shopify-Shop-Domain'))->installed()->first();

        $shop->setInfo($request->all());

        $shop->save();

        return $request->input();
    }

    private function getShopInfo(Shop $shop)
    {
        $client = $this->getShopifyClient($shop);

        $info = [];

        try {
            $info = $client->call('GET', '/admin/shop.json');
        } catch (\Exception $e) {
            Log::error($e);
        }

        return $info;
    }
}
