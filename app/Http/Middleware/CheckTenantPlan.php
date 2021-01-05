<?php

namespace App\Http\Middleware;

use Closure;

class CheckTenantPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $plan)
    {
        if ( tenant()->plan == "large" ) {
            return $next($request);
        }

        if ( tenant()->plan !== $plan ) {
            return abort(403);
        }

        return $next($request);
    }
}
