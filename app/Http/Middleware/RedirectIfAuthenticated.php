<?php

namespace App\Http\Middleware;

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
        if($guard == 'staff') {
            if (Auth::guard($guard)->check()) {
                return redirect('/system');
            }
        }

        if($guard == 'merchant_staff') {
            if (Auth::guard($guard)->check()) {
                return redirect('/merchant');
            }
        }
        return $next($request);
    }
}
