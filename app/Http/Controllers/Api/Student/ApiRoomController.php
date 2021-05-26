<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\RoomCollection;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\ResultTestRepository;
use App\Repositories\Room\RoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiRoomController extends ApiBaseController
{
    //
    private $roomRepository;
    private $resultTestRepository;
    private $resultDetailRepository;

    public function __construct(RoomRepository $roomRepository, ResultTestRepository $resultTestRepository, ResultDetailRepository $resultDetailRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->resultTestRepository = $resultTestRepository;
        $this->resultDetailRepository = $resultDetailRepository;
    }

    public function joinRoom(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $room = $this->roomRepository->find(decrypt($id));
        if (!$room) {
            return self::responseJSON(400, false, 'Not exist this room');
        }
        return self::responseJSON(200, true, 'Join room success', ['room' => new RoomCollection($room)])
            ->cookie('r_id', $id);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:100',
        ]);
        if ($validator->fails()) {
            return self::responseJSON(422, false, $validator->errors()->first(), ['errors' => $validator->errors()]);
        }
        $room_id = decrypt($request->header('auth-room'));
        $result_test_id = $this->resultTestRepository->getResultTestOnline($room_id)->id;
        $result_detail = $this->resultDetailRepository->create([
            'student_name' => $request->get('name'),
            'scores' => 0,
            'student_choices' => null,
            'result_id' => $result_test_id
        ]);
        return self::responseJSON(200, true, 'Register success',
            [
                'name' => $request->get('name'),
                'rd_id' => encrypt($result_detail->id)
            ])->cookie('rd_id', encrypt($result_detail->id));
    }
}
