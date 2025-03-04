<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $assigned_user_types)
    {
        $logged_in_user_type = auth()->user()->user_type;
        
        $array_user_types = [];
        $array_user_types = explode('|', $assigned_user_types);

        if(in_array($logged_in_user_type, $array_user_types)){
            return $next($request);
        } else {
            abort('403','Unauthorized page access');
        }
    }
}
