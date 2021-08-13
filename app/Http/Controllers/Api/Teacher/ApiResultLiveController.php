<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\QuizCopyCollectionLive;
use App\Http\Resources\ResultCollectionLive;
use App\Http\Resources\RoomCollection;
use App\Models\QuestionCopy;
use App\Models\QuizCopy;
use App\Models\Room;
use App\Repositories\QuizCopy\QuizCopyRepository;
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

    public function __construct(RoomRepository $roomRepository,
                                ResultTestRepository $resultTestRepository,
                                QuizCopyRepository $quizCopyRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->resultTestRepository = $resultTestRepository;
        $this->quizCopyRepository = $quizCopyRepository;
    }

    public function getResultRoom(Request $request, Room $room): JsonResponse
    {
        try {
            $this->authorize('view', $room);
        } catch (AuthorizationException $e) {
            return self::response403($e->getMessage());
        }
        if (!$room->status)
            return self::responseJSON(401, false, "This room is not online");
        $result_test = $this->resultTestRepository->getResultTestOnline($room->id);
        if (!$result_test)
            return self::response404("Not found result test");
        $quiz = $this->quizCopyRepository->find($result_test->quiz_copy_id);
        $result_live = $result_test->resultDetails()->get();
        $this->_solveResultLive($result_live);
        return self::responseJSON(200, true, "Dataset", [
            'key_channel' => $result_test->id,
            'room' => new RoomCollection($room),
            'quiz' => new QuizCopyCollectionLive($quiz),
            'result_live' => $result_live
        ]);
    }

    private function _solveResultLive(&$collection) {
        $collection->transform(function ($record) {
            $record->student_choices = json_decode($record->student_choices, true);
            $record->student_choices = array_map(function ($r) {
                $id = array_key_first($r);
                $r["question_id"] = $id;
                $r["student_choice"] = $r[$id];
                unset($r[$id]);
                return $r;
            }, $record->student_choices);
            unset($record["deleted_at"]);
            unset($record["created_at"]);
            unset($record["updated_at"]);
            unset($record["room_pending_id"]);
            return $record;
        })->toArray();

    }
}
