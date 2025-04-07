<?php

namespace App\Http\Middleware;

use App\Models\ViewPermissions;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->is_a_cms_user()) {
                return redirect(route('dashboard'));
            } else if (Auth::user()->is_a_member_user()) {
                return redirect(route('member.dashboard'));
            } else if (Auth::user()->is_a_incomplete_member_user()) {
                return redirect(route('product.front.list'));
            }
        }

        return $next($request);
    }
}
