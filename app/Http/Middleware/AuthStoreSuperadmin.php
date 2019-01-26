<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class AuthStoreSuperadmin
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
        if (!Auth::guard('store')->check() && !Auth::guard('superadmin')->check()) {
            return redirect()->route('store-login-get');
        } else if (Auth::guard('superadmin')->check() && !Session::has('superadmin_store_id')) {
            return redirect()->route('superadmin-index');
        }

        return $next($request);
    }
}
