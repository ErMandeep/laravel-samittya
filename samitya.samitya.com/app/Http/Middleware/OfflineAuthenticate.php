<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;
use Closure;

/**
 * Class Authenticate.
 */
class OfflineAuthenticate 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    public function handle($request , Closure $next)
    {
        if (auth()->guard('offline')->check() ) {

            return $next($request);

        }

        return redirect()->route('offline.login');
    }

}
