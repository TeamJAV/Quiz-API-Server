<?php

namespace App\Http\Middleware;

use App\Models\Room;
use Closure;

class JoinRoomMiddleware
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
        if ($request->route()->parameter('room') != $request->session()->get('room')['id']){
            return redirect()->back();
        }
        if (Room::findOrFail($request->route()->parameter('room'))->status == 0){
            return redirect()->route('student.wait');
        }
        return $next($request);
    }
}
