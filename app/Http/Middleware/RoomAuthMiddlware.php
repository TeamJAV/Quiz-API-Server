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
            return response()->json([
                'status' => 403,
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        return $next($request);
    }

    private function _check($request)
    {
        if (!$request->header('r_id')) {
            return false;
        }
        return Room::find(decrypt($request->header('r_id')));
    }
}
