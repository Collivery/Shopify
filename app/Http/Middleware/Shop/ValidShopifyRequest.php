<?php

namespace App\Http\Middleware\Shop;

use Closure;

class ValidShopifyRequest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $client = new \ShopifyClient(null, null, null, config('shopify.secret'));

        if (!$client->validateSignature(array_slice($_GET, 0, 10))) {
            return abort(400, 'Bad reqeust sent');
        }

        return $next($request);
    }
}
