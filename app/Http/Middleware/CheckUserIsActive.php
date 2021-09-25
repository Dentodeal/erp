<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserIsActive
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
        if (!\Auth::user()->active) {
            \Auth::guard('web')->logout();
        }
        return $next($request);
    }
}
