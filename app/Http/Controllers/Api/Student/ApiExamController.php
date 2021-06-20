<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(Request $request): JsonResponse
    {
        if (!$request->filled('answer')) {
            return self::responseJSON(400, false, 'The answer is required');
        }
        $room = self::currentRoom($request);
        $result_detail = $this->currentResultDetail($request);
        $result_test = $this->resultTestRepository->getResultTestOnline($room->id);
        $this->resultDetailRepository->solveResult($result_detail, $request->input('answer'), $result_test->quiz_copy_id);
        return self::responseJSON(201, true);
    }
}
