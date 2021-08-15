<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\HistoryTest\ResultTestCollection;
use App\Http\Resources\QuestionCopyCollection;
use App\Http\Resources\QuizCopyCollectionLive;
use App\Models\QuestionCopy;
use App\Models\ResultDetail;
use App\Models\ResultTest;
use App\Repositories\QuizCopy\QuizCopyRepository;
use App\Repositories\ResultDetail\ResultDetailRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultController extends ApiBaseController
{
    private $resultDetailRepository;
    private $quizCopyRepository;

    public function __construct(ResultDetailRepository $resultDetailRepository,
                                QuizCopyRepository $quizCopyRepository)
    {
        $this->resultDetailRepository = $resultDetailRepository;
        $this->quizCopyRepository = $quizCopyRepository;
    }

    public function allHistoryTest(Request $request): JsonResponse
    {
        $result = auth()->user()->resultTests()->where("status", 0)->orderByDesc("updated_at")->get();
        return self::responseJSON(200, true, 'Thành công', ResultTestCollection::collection($result));
    }

    public function detailHistory(Request $request, ResultTest $result_test): JsonResponse
    {
        if (auth()->user()->cant("view", $result_test)) {
            return self::response403();
        }
        $quiz_copy = $this->quizCopyRepository->find($result_test->quiz_copy_id);
        $result_live = $result_test->resultDetails()->get();
        return self::responseJSON(200, true, 'Success', [
            'quiz' => new QuizCopyCollectionLive($quiz_copy),
            'result_live' => $this->resultDetailRepository->formatResultDetail($result_live),
            'percent' => $this->resultDetailRepository->getPercent($result_live)
        ]);
    }

    public function getQuestionResultDetail(ResultTest $result, QuestionCopy $question): JsonResponse
    {
        try {
            $this->authorize("view", $result);
        } catch (AuthorizationException $e) {
            return self::response403($e->getMessage());
        }
        $detail = ResultDetail::query()->where('result_id', $result->id)->get();
        $options = array_keys(json_decode($question->choices, true));
        $unit = [];
        foreach ($options as $option) {
            $unit[$option] = [
                'choose' => 0,
                'percent' => '',
            ];
        }
        $number_student = $detail->count();
        $student_answer = 0;
        $detail->each(function ($student) use ($question, &$student_answer, &$unit) {
            $choices = json_decode($student["student_choices"], true);
            foreach ($choices as $choice) {
                $id_question = array_key_first($choice);
                if ($id_question == $question->id) {
                    $student_choices = $choice[$id_question]['choices'];
                    $student_answer += empty($student_choices) ? 0 : 1;
                    foreach ($student_choices as $student_choice) {
                        if (isset($unit[$student_choice])) {
                            $unit[$student_choice]["choose"] += 1;
                        }
                    }
                }
            }
        });
        foreach ($unit as $key => &$value) {
            $value["percent"] = round($value["choose"] * 100 / $number_student, 1) . '%';
        }
        return self::responseJSON(200, true, 'Success',
            [
                'question' => new QuestionCopyCollection($question),
                'student_answer' => $student_answer,
                'total_student' => $number_student,
                'percent' => $unit,
            ]);
    }
}
