<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatedMiddleware
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
        if(Auth::check()){
            return $next($request);
        } else {
            return redirect(url(route('admin.login')));

//            $currentUrl = url()->current();
//            if(strpos($currentUrl, 'admin') !== false) {
//                return redirect(route('login'));
//            } else if (strpos($currentUrl, 'member') !== false) {
//                return redirect(route('member.login'));
//            } else {
//                return redirect(route('customer-front.login'));
//            }
        }
    }
}
