<?php

namespace App\Http\Controllers\Api\Student;

use App\Events\SubmitQuestionEvent;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\QuizCopyCollection;
use App\Models\QuizCopy;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        if (!$request->filled('answer')) {
            return self::responseJSON(400, false, 'The answer is required');
        }
        $room = self::currentRoom($request);
        $result_detail = $this->currentResultDetail($request);
        if (is_null($result_detail))
            return self::response403();
        try {
            $result_test = $this->resultTestRepository->getResultTestOnline($room->id);
            $this->resultDetailRepository->solveResult($result_detail, $request->input('answer'), $result_test->quiz_copy_id);
            event(new SubmitQuestionEvent($result_test));
            return self::responseJSON(201, true);
        } catch (\Exception $exception) {
            return self::responseJSON(500, false, $exception->getMessage());
        }
    }

    public function result(Request $request): JsonResponse
    {
        $result_detail = $this->currentResultDetail($request);
        if (is_null($result_detail))
            return self::response403();
        return self::responseJSON(200, true, 'Result', [
            'student' => $result_detail->student_name,
            'student_choices' => json_encode($result_detail->student_choices),
            'time_do_seconds' => Carbon::now()->diffInSeconds(Carbon::createFromFormat("%Y-%m-%d %H-%i-%s", $result_detail->time_joined))
        ]);
    }

    public function ip(): JsonResponse
    {
        return self::responseJSON(200, true, "IP", [
            "ip" => $ip = $this->getIp()
        ]);
    }
}
