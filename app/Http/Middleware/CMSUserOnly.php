<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CMSUserOnly
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
            if(Auth::user()->is_a_cms_user()) {
                return $next($request);
            } else if(Auth::user()->is_a_member_user()) {
                return redirect(route('member.dashboard'));
            }
        }

        return redirect(url('/'));
    }
}
