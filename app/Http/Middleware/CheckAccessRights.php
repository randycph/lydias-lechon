<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Role;
use Closure;

class CheckAccessRights
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $routeId
     * @return mixed
     */
    public function handle($request, Closure $next, $routeId)
    {
        if (auth()->user()->is_an_admin()) {
            //logger('admin');
            return $next($request);
        }

        if (auth()->user()->assign_role->has_permission_to_route($routeId))
        {
            //logger('admin');
            return $next($request);
        }

        return response()->view('components.unauthorize-access');
    }
}
