<?php

namespace App\Http\Middleware;

use Closure;

class ForeJsonResponse
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
        $request->header('Accept', 'application/json');
        return $next($request);
    }
}
