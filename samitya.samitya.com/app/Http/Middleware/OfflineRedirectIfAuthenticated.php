<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class RedirectIfAuthenticated.
 */
class OfflineRedirectIfAuthenticated
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
        if (auth()->guard('offline')->check()  ) {
            
            return redirect()->route('offline.dashboard');
        }
        return $next($request);
    }
}
