<?php

namespace App\Http\Controllers\Api\Student;

use App\Events\SubmitQuestionEvent;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\QuizCopyCollection;
use App\Http\Resources\RoomCollection;
use App\Models\QuizCopy;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Location;

class ApiExamController extends ApiBaseController
{
    //
    private $resultDetailRepository;
    private $resultTestRepository;

    public function __construct(ResultDetailRepository $resultDetailRepository, ResultTestRepository $resultTestRepository)
    {
        $this->resultDetailRepository = $resultDetailRepository;
        $this->resultTestRepository = $resultTestRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $current_room = self::currentRoom($request);
        if (is_null($current_room)) {
            return self::responseJSON(403, false, "Invalid request");
        }
        $result_test = $this->resultTestRepository->getResultTestOnline($current_room->id);
        return self::responseJSON(200, true, "Content quiz", [
            'quiz' => new QuizCopyCollection(QuizCopy::with("questionCopies")->find($result_test->quiz_copy_id)),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'answer' => 'required|array',
            'answer.*' => 'required',
            'answer.question_id' => 'required|integer',
            'answer.choices' => 'nullable|array',
            'answer.type' => 'required|string|in:' . self::MULTIPLE . ',' . self::SHORT_ANSWER . ',' . self::TRUE_FALSE,
            'is_finished' => 'required|boolean',
        ], [
            'answer.required' => 'The answer is required',
            'answer.array' => 'The answer is must string',
            'is_finished.required' => 'Missing the status of the exam',
            'is_finished.boolean' => 'Status must be boolean',
        ]);
        try {
            if ($validator->fails()) {
                return self::responseJSON(422, false, $validator->errors()->first());
            }
            $result_detail = $this->currentResultDetail($request);
            if (is_null($result_detail)) {
                return self::response403();
            }
            $room = self::currentRoom($request);
            $result_test = $this->resultTestRepository->getResultTestOnline($room->id);
            $this->resultDetailRepository->solveResult($result_detail, $request->input('answer'), $result_test->quiz_copy_id);
            event(new SubmitQuestionEvent($result_test));
            if ($request->input("is_finished")) {
                $result_detail->is_finished = 1;
                $result_detail->save();
                return self::responseJSON(200, true, "Your exam is finished", [
                    "link_result" => route("api.result-test", $result_detail->id)
                ]);
            }
            return self::responseJSON(201, true);
        } catch (\Exception $e) {
            return self::responseJSON(500, false, $e->getMessage());
        }
    }

    public function result(Request $request, $id): JsonResponse
    {
        $result_detail = $this->resultDetailRepository->find($id);
        if (!$result_detail) {
            return self::response404("Not found student result");
        }
        $now = Carbon::now();
        $last_time = Carbon::parse($result_detail->updated_at);
        if ($now->gt($last_time) && $now->diffInMinutes($last_time) > 10) {
            return self::response404();
        }
        return self::responseJSON(200, true, 'This link will expire after 10 minutes',
            [
                'student' => $result_detail->student_name,
                'student_choices' => json_decode($result_detail->student_choices),
                'scores' => $result_detail->scores,
                'time_joined' => $result_detail->time_joined,
                'time_end' => $result_detail->time_end,
                'time_do_seconds' => $last_time->diffInSeconds(Carbon::parse($result_detail->time_joined)),
//            'result_test_id' => $result_detail->result_id,
            ]);
    }

    public function infoStudent(Request $request): JsonResponse
    {
        $current_room = self::currentRoom($request);
        $result_detail = self::currentResultDetail($request);
        if (!$current_room || !$result_detail) {
            return self::response404();
        }
        return self::responseJSON(200, true, "Info student", [
            'result_detail' => [
                'name' => $result_detail->student_name,
                'rd_id' => $result_detail->id,
                'key_channel' => $result_detail->timestamp_out,
                'time_end' => $result_detail->time_end
            ],
            'room' => new RoomCollection($current_room),
        ]);
    }

    public function ip(): JsonResponse
    {
        return self::responseJSON(200, true, "IP", [
            "ip" => $ip = $this->getIp()
        ]);
    }
}
