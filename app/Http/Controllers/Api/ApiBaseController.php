<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResultDetail;
use App\Models\Room;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    //
    const MULTIPLE = 'multiple';
    const SHORT_ANSWER = 'short-answer';
    const TRUE_FALSE = 'true-false';
    const HELL_ID = 999;

    protected static function response403($message = 'This action is forbidden'): \Illuminate\Http\JsonResponse
    {
        return self::responseJSON(403, false, $message);
    }

    protected static function response404($message = 'Not found'): \Illuminate\Http\JsonResponse
    {
        return self::responseJSON(404, false, $message);
    }

    protected static function currentRoom(Request $request)
    {
        $id = $request->header('r_id') ? $request->header('r_id') : null;
        return Room::find($id);
    }

    protected static function currentResultDetail(Request $request)
    {
        $id = $request->header('rd_id') ? $request->header('rd_id') : null;
        return ResultDetail::find($id);
    }

    public function getIp(): ?string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

}
