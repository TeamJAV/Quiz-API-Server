<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionCopyCollection;
use App\Http\Resources\ResultCollection;
use App\Models\QuestionCopy;
use App\Models\ResultDetail;
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

    public function getQuestionResultDetail($result_id, $question_copy_id){
        $result = ResultTest::find($result_id);
        $ans_list = array();
        if(!$result){
            return self::responseJSON(404, false, 'Không tìm thấy');
        }if(!auth()->user()->can("view", $result)){
            return self::responseJSON(403, false, 'Không tìm thấy lịch sử');
        }
        $quiz = $result->quiz_copy_id;
        $question = QuestionCopy::query()->where('quiz_copy_id', '=', $quiz)->find($question_copy_id); // lấy câu hỏi với id đầu vào
        $detail = ResultDetail::query()->where('result_id', '=', $result->id)->get(); // lấy các bài thi của thí sinh với id của result
        $num_student = count((array) json_decode($detail));
        for($i = 0; $i < $num_student; $i++) {
            $a = json_decode($detail[$i]['student_choices'], true);
            foreach ($a as $key=>$val){
                if($key == $question->id){
                    foreach($val['choices'] as $choice=>$data){
                        array_push($ans_list, $data);
                    }
                }
            }
        }

        sort($ans_list);
        $percent = array();
        for($i = 0; $i < $num_student; $i++){
            if (array_key_exists($ans_list[$i],$percent)){
                $percent[$ans_list[$i]] += 1;
            }else{
                $percent[$ans_list[$i]] = 1;
            }
        }

        foreach($percent as $key=>$value){
            $percent[$key] = $value / ($num_student) * 100;
        }
        return self::responseJSON(200, true, 'Thành công', ['question'=> new QuestionCopyCollection($question),
                                                                                'percent'=>$percent]);
    }
}
