<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\QuizCopyCollection;
use App\Http\Resources\RoomCollection;
use App\Models\QuizCopy;
use App\Repositories\QuizCopy\QuizCopyRepository;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use App\Repositories\Room\RoomRepository;
use Illuminate\Contracts\Encryption\DecryptException;
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
        try {
            $room = $this->roomRepository->find(decrypt($id));
        } catch (DecryptException $e) {
            return self::responseJSON(400, false, $e->getMessage());
        }
        if (!$room) {
            return self::responseJSON(400, false, 'Not exist this room');
        }
        // Trả về r_id là id mã hóa của phòng thi, lưu ở cookie hoặc local storage để lần sau gửi lên cùng với headers
        return self::responseJSON(200, true, 'Join room success', ['r_id' => encrypt($room->id)])
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
            return self::responseJSON(400, false, 'Not exist this room');
        }
        // Trả về r_id là id mã hóa của phòng thi, lưu ở cookie hoặc local storage để lần sau gửi lên cùng với headers
        return self::responseJSON(200, true, 'Join room success', ['r_id' => encrypt($room->id)])
            ->cookie('r_id', encrypt($room->id));
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
            return self::responseJSON(422, false, $validator->errors()->first(), ['errors' => $validator->errors()]);
        }
        $current_room = self::currentRoom($request);
        $result_test = $this->resultTestRepository->getResultTestOnline($current_room->id);
        $result_detail = $this->resultTestRepository->creatResultDetailForStudent($result_test->quiz_copy_id, $result_test, $request->get("name"), $current_room->time_offline);
        return self::responseJSON(200, true, 'Register success',
            [
                'result_detail' => [
                    'name' => $request->get('name'),
                    'rd_id' => encrypt($result_detail->id),
                ],
                'room' => new RoomCollection($current_room),
//                'quiz' => new QuizCopyCollection(QuizCopy::with("questionCopies")->find($result_test->quiz_copy_id)),
            ])->cookie('rd_id', encrypt($result_detail->id));
    }
}
