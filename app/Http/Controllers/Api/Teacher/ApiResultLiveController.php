<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\QuizCopyCollectionLive;
use App\Http\Resources\RoomCollection;
use App\Models\Room;
use App\Repositories\QuizCopy\QuizCopyRepository;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use App\Repositories\Room\RoomRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiResultLiveController extends ApiBaseController
{
    //
    private $resultTestRepository;
    private $roomRepository;
    private $quizCopyRepository;
    private $resultDetailRepository;

    public function __construct(RoomRepository $roomRepository,
                                ResultTestRepository $resultTestRepository,
                                QuizCopyRepository $quizCopyRepository,
                                ResultDetailRepository $resultDetailRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->resultTestRepository = $resultTestRepository;
        $this->quizCopyRepository = $quizCopyRepository;
        $this->resultDetailRepository = $resultDetailRepository;
    }

    public function getResultRoom(Request $request, Room $room): JsonResponse
    {
        try {
            $this->authorize('view', $room);
        } catch (AuthorizationException $e) {
            return self::response403($e->getMessage());
        }
        $result_test = $this->resultTestRepository->getResultTestOnline($room->id);
        if (!$room->status || !$result_test) {
            return self::response404();
        }
        $quiz = $this->quizCopyRepository->find($result_test->quiz_copy_id);
        $result_live = $result_test->resultDetails()->get();
        return self::responseJSON(200, true, "Dataset", [
            'key_channel' => $result_test->id,
            'room' => new RoomCollection($room),
            'quiz' => new QuizCopyCollectionLive($quiz),
            'result_live' => $this->resultDetailRepository->formatResultDetail($result_live)
        ]);
    }
}
