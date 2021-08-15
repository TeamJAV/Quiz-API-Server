<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\HistoryTest\ResultTestCollection;
use App\Http\Resources\QuestionCopyCollection;
use App\Http\Resources\ResultCollection;
use App\Models\QuestionCopy;
use App\Models\ResultDetail;
use App\Models\ResultTest;
use App\Repositories\ResultDetail\ResultDetailRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultController extends ApiBaseController
{
    private $resultDetailRepository;

    public function __construct(ResultDetailRepository $resultDetailRepository)
    {
        $this->resultDetailRepository = $resultDetailRepository;
    }

    public function allHistoryTest(Request $request): JsonResponse
    {
        $result = auth()->user()->resultTests()->where("status", 0)->orderByDesc("updated_at")->get();
        return self::responseJSON(200, true, 'Thành công', ResultTestCollection::collection($result));
    }

    public function detailHistory(Request $request, $id): JsonResponse
    {
        $result = ResultTest::find($id);
        if (!$result) {
            return self::response404();
        }
        if (!auth()->user()->can("view", $result)) {
            return self::response403('Not found history');
        }
        return self::responseJSON(200, true, '', new ResultCollection($result));
    }

    public function getQuestionResultDetail($result_id, $question_copy_id): JsonResponse
    {
        $result = ResultTest::find($result_id);
        $ans_list = [];
        if (!$result) {
            return self::response404();
        }
        if (!auth()->user()->can("view", $result)) {
            return self::response403('Not found history');
        }
        $quiz = $result->quiz_copy_id;
        // lấy câu hỏi với id đầu vào
        $question = QuestionCopy::query()->where('quiz_copy_id', $quiz)->find($question_copy_id);
        // lấy các bài thi của thí sinh với id của result
        $detail = ResultDetail::query()->where('result_id', $result->id)->get();
        $num_student = count((array)json_decode($detail));
        for ($i = 0; $i < $num_student; $i++) {
            $a = json_decode($detail[$i]['student_choices'], true);
            foreach ($a as $key => $val) {
                if ($key == $question->id) {
                    foreach ($val['choices'] as $choice => $data) {
                        array_push($ans_list, $data);
                    }
                }
            }
        }
        sort($ans_list);
        $percent = array();
        for ($i = 0; $i < $num_student; $i++) {
            if (array_key_exists($ans_list[$i], $percent)) {
                $percent[$ans_list[$i]] += 1;
            } else {
                $percent[$ans_list[$i]] = 1;
            }
        }

        foreach ($percent as $key => $value) {
            $percent[$key] = $value / ($num_student) * 100;
        }
        return self::responseJSON(200, true, 'Success',
            [
                'question' => new QuestionCopyCollection($question),
                'percent' => $percent
            ]);
    }
}
