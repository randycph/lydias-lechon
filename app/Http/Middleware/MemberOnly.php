<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MemberOnly
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
                abort('403','Unauthorized page access');
            } else if(Auth::check() && Auth::user()->is_a_member_user()) {
                return $next($request);
            }
        }

        return redirect(url('/'));
    }
}
