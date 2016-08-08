<?php

namespace App\Http\Middleware;

use Closure;

class MustSubscribe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param null                      $plan
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $plan = null)
    {
        $user = $request->user();

        if($user && !!$user->subscribed($plan))
            return $next($request);

        return redirect('/');
    }
}
