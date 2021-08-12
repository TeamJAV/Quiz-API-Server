<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\QuizCopyCollection;
use App\Http\Resources\RoomCollection;
use App\Models\QuizCopy;
use App\Models\ResultDetail;
use App\Repositories\QuizCopy\QuizCopyRepository;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use App\Repositories\Room\RoomRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiRoomController
 * @package App\Http\Controllers\Api\Student
 */
class ApiRoomController extends ApiBaseController
{
    private $roomRepository;
    private $resultTestRepository;
    private $resultDetailRepository;
    private $quizCopyRepository;

    public function __construct(RoomRepository $roomRepository, ResultTestRepository $resultTestRepository, ResultDetailRepository $resultDetailRepository, QuizCopyRepository $quizCopyRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->resultTestRepository = $resultTestRepository;
        $this->resultDetailRepository = $resultDetailRepository;
        $this->quizCopyRepository = $quizCopyRepository;
    }

    public function joinRoom(Request $request, $id): JsonResponse
    {
        $room = $this->roomRepository->find($id);
        if (!$room) {
            return self::response404('Not found this room');
        }
        return self::responseJSON(200, true, 'Join room success', ['r_id' => $room->id])
            ->cookie('r_id', $id);

    }

    public function joinRoomByName(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => 'Room name is required'
        ]);
        if ($validator->fails()) {
            return self::responseJSON(422, false, $validator->errors()->first(), ['errors' => $validator->errors()]);
        }
        $room = $this->roomRepository->getRoomByName($request->input('name'));
        if (!$room) {
            return self::response404('Not found this room');
        }
        if (!$room->status) {
            return self::responseJSON(200, true, 'Join success, waiting the room online', ['r_id' => $room->id])
                ->cookie('r_id', $room->id);
        }
        return self::responseJSON(200, true, 'Join room success', ['r_id' => $room->id])
            ->cookie('r_id', $room->id);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:100',
        ], [
            'name.required' => 'Please enter your name',
            'name.min' => 'Your name must be at least 1 character',
            'name.max' => 'Your name must be at most 100 characters',
        ]);
        if ($validator->fails()) {
            return self::responseJSON(422, false, $validator->errors()->first());
        }
        $current_room = self::currentRoom($request);
        $result_test = $this->resultTestRepository->getResultTestOnline($current_room->id);
        // If result_test null means room offline else room online
        if ($result_test == null) {
            $result_detail = $this->resultDetailRepository->create([
                'student_name' => $request->get('name'),
                'scores' => 0,
                'result_id' => self::HELL_ID,
                'room_pending_id' => $current_room->id
            ]);
            $mess = "Submit success, please waiting room online";
        } else {
            $result_detail = $this->resultTestRepository->creatResultDetailForStudent(
                $result_test->quiz_copy_id, $result_test, $request->input("name"), $current_room->time_offline);
            $mess = "Submit success";
        }

        return self::responseJSON(200, true, $mess,
            [
                'result_detail' => [
                    'name' => $result_detail->student_name,
                    'rd_id' => $result_detail->id,
                    'key_channel' => $result_detail->timestamp_out,
                    'time_end' => $result_detail->time_end
                ],
                'room' => new RoomCollection($current_room),
            ])->cookie('rd_id', $result_detail->id);
    }
}
