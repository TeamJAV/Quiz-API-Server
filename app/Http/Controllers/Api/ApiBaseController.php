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

    protected static function response403($message = 'This action is forbidden'): \Illuminate\Http\JsonResponse
    {
        return self::responseJSON(403, false, $message);
    }

    protected static function currentRoom(Request $request)
    {
        $id = decrypt($request->header('r_id'));
        $room = Room::find($id);
        if (!$room) {
            return null;
        }
        return $room;
    }

    protected static function currentResultDetail(Request $request)
    {
        $id = decrypt($request->header('rd_id'));
        $result_detail = ResultDetail::find($id);
        if (!$result_detail) {
            return null;
        }
        return $result_detail;
    }
}
