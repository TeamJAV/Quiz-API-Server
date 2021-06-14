<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultCollection;
use App\Models\ResultTest;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function allHistoryTest(Request $request){
        $result = auth()->user()->resultTests()->where('status', 0)->get();
        return self::responseJSON(200, true, 'Thành công', ResultCollection::collection($result));
    }

    public function detailHistory(Request $request, $id){
        $result = ResultTest::find($id);
        if(!$result){
            return self::responseJSON(404, false, 'Không tìm thấy');
        }if(!auth()->user()->can("view", $result)){
            return self::responseJSON(403, false, 'Không tìm thấy lịch sử');
        }
        return self::responseJSON(200, true, 'Thành công', new ResultCollection($result));
    }
}
