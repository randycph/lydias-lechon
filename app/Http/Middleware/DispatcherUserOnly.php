<?php

namespace App\Http\Middleware;

use Closure;

class DispatcherUserOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if(Auth::user()->is_a_dispatcher_user()) {
                return $next($request);
            } else {
                abort('403','Unauthorized page access');
            }
        }
    }
}
