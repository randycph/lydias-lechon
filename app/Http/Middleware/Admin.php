<?php

namespace App\Http\Middleware;
use Closure;
use Auth;

class Admin
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
        if(Auth::check() && Auth::user()->role_id == 1){
            // 1 for admin role
            return $next($request);
        }

        return response()->view('components.unauthorize-access');
    }
}
