<?php

namespace App\Http\Middleware;

use Closure;

class RoomAuthMiddlware
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
        if (!$request->session()->has('room')){
            return redirect()->route('student.login.room');
        }
        return $next($request);
    }
}
