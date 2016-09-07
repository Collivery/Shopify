<?php

namespace App\Http\Middleware\Shop;

use Closure;
use Illuminate\Http\Request;

class ValidWebhook
{
    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->check('php://input', $request->header('X-Shopify-Hmac-Sha256'))) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }

    /**
     * @param $stream
     * @param $hmac
     *
     * @return bool
     */
    private function check($stream, $hmac)
    {
        $genHmac = base64_encode(hash_hmac_file('sha256', $stream, config('shopify.secret'), true));

        return $hmac === $genHmac;
    }
}
