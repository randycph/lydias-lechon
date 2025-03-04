<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuestOnly
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
        if (Auth::guest()) {
            return $next($request);
        } else {
            if(Auth::user()->is_a_cms_user()) {
                return redirect(route('dashboard'));
            } else if(Auth::user()->is_a_member_user()) {
                return redirect(route('member.dashboard'));
            } else {
                return redirect(url('/'));
            }
        }
    }
}
