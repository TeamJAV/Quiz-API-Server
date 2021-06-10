<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\RoomCollection;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use App\Repositories\Room\RoomRepository;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiRoomController
 * @package App\Http\Controllers\Api\Student
 */
class ApiRoomController extends ApiBaseController
{
    //
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var ResultTestRepository
     */
    private $resultTestRepository;
    /**
     * @var ResultDetailRepository
     */
    private $resultDetailRepository;

    /**
     * ApiRoomController constructor.
     * @param RoomRepository $roomRepository
     * @param ResultTestRepository $resultTestRepository
     * @param ResultDetailRepository $resultDetailRepository
     */
    public function __construct(RoomRepository $roomRepository, ResultTestRepository $resultTestRepository, ResultDetailRepository $resultDetailRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->resultTestRepository = $resultTestRepository;
        $this->resultDetailRepository = $resultDetailRepository;
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
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
        return self::responseJSON(200, true, 'Join room success', ['room' => new RoomCollection($room)])
            ->cookie('r_id', $id);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
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
        return self::responseJSON(200, true, 'Join room success', ['room' => new RoomCollection($room)])
            ->cookie('r_id', encrypt($room->id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
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
                'name' => $request->get('name'),
                'rd_id' => encrypt($result_detail->id)
            ])->cookie('rd_id', encrypt($result_detail->id));
    }

    private function _addStudentChoices($id_quiz)
    {

    }
}
