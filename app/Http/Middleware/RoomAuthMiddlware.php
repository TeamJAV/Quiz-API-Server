<?php

namespace App\Http\Middleware;

use App\Models\Room;
use Closure;

class RoomAuthMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->_check($request)) {
            return response([
                'status' => 403,
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        return $next($request);
    }

    private function _check($request)
    {
        if (!$request->header('auth-room')) {
            return false;
        }
        return Room::find(decrypt($request->header('auth-room')));
    }
}
