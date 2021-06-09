<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function allHistoryTest(Request $request){
        $result = auth()->user()->resultTests()->where('status', 0)->get();
        return self::responseJSON(200, true, 'Thành công', $result);
    }

    public function detailHistory(Request $request, $id){
        $result = auth()->user()->resultTests()->where('id', $id)->first();
        if(!$result){
            return self::responseJSON(404, false, 'Không tìm thấy', $result);
        }
        return self::responseJSON(200, true, 'Thành công', $result);
    }
}
