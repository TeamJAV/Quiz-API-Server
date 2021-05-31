<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Events\RoomOnlineEvent;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Room\LaunchRoomRequest;
use App\Http\Requests\Room\NewRoomRequest;
use App\Http\Resources\RoomCollection;
use App\Models\Room;
use App\Repositories\ResultTest\ResultTestRepository;
use App\Repositories\Room\RoomRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiRoomController extends ApiBaseController
{
    //
    private $roomRepository;
    private $userRepository;
    private $resultTestRepository;

    public function __construct(RoomRepository $roomRepository, UserRepository $userRepository, ResultTestRepository $resultTestRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
        $this->resultTestRepository = $resultTestRepository;
    }


    public function index(Request $request, $search = null, $orderBy = "id", $type = "asc"): JsonResponse
    {
        $rooms = $this->roomRepository->getRoomByNamePaginate(auth()->id(), $search, $orderBy, $type, $request->get('trash'));
        return self::responseJSON(200, true, 'List all rooms', ['room' => RoomCollection::collection($rooms)]);
    }

    public function store(NewRoomRequest $request): JsonResponse
    {
        $attr = [
            'name' => $request->get('name'),
            'user_id' => auth()->id()
        ];
        if ($request->filled('id')) {
            $room = $this->roomRepository->find($request->get('id'));
            if (auth()->user()->cant('update', $room)) {
                return self::response403('This room is not belong to you');
            }
            $this->roomRepository->update($request->get('id'), $attr);
            return self::responseJSON(200, true, 'Update success', ['room' => new RoomCollection($room)]);
        }
        $room = $this->roomRepository->create($attr);
        return self::responseJSON(200, true, 'Create success', new RoomCollection($room));
    }

    public function share(Room $room): JsonResponse
    {
        if (!$room) {
            return self::responseJSON(400, false, 'Not exist this room');
        }
        if (auth()->user()->cant('share', $room)) {
            return self::response403('This room is not belong to you');
        }
        $link = route('api.room-join', encrypt($room->id));
        return self::responseJSON(200, true, 'Link join room', ['link' => $link]);
    }


    public function launchRoom(LaunchRoomRequest $request): JsonResponse
    {
        $room = $this->roomRepository->find($request->get('id'));
        try {
            $this->authorize('update', $room);
        } catch (AuthorizationException $e) {
            return self::response403('This room is not belong to you');
        }
        if ($room->status == 1) {
            return self::responseJSON(422, false, 'You must stop this room before launch again');
        }
        $shuffle_answer = $request->get('shuffle_answer') == true ? 1 : 0;
        $shuffle_question = $request->get('shuffle_question') == true ? 1 : 0;
        $time_offline = $request->filled('time_offline') ? $request->get('time_offline') : null;
        $room = $this->roomRepository->setUpRoomOnline($request->get('id'), [
            'shuffle_question' => $shuffle_question,
            'shuffle_answer' => $shuffle_answer,
            'status' => 1,
            'time_offline' => $time_offline
        ]);
        $resultTest = $this->resultTestRepository->create([
            'status' => 1,
            'date_create' => Carbon::now()->toDateTimeString(),
            'room_id' => $request->get('id'),
            'user_id' => $room->user_id,
            'quiz_copy_id' => $request->get('id_quiz'),
        ]);
        event(new RoomOnlineEvent($room));
        $context = [
            'room' => new RoomCollection($room),
            'quiz' => [
                'id' => $request->get('id_quiz'),
            ],
            'result_test' => [
                'id' => $resultTest->id,
            ]
        ];
        return self::responseJSON(200, true, 'Room online', $context);
    }

    public function stopLaunchRoom(Room $room): JsonResponse
    {
        try {
            $this->authorize('update', $room);
        } catch (AuthorizationException $e) {
            return self::response403($e->getMessage());
        }
        $room = $this->roomRepository->setUpRoomOffline($room->id);
        try {
            $result_test = $this->resultTestRepository->getResultTestOnline($room->id);
            $this->resultTestRepository->update($result_test->id, ['status' => 0]);
            event(new RoomOnlineEvent($room));
            return self::responseJSON(200, true, 'Stop launch room', new RoomCollection($room));
        } catch (\Exception $e) {
            return self::responseJSON(410, false, 'Room already offline');
        }
    }

    public function delete(Room $room): JsonResponse
    {
        try {
            $this->authorize('delete', $room);
            if ($room->status == 1)
                return self::responseJSON(422, false, 'You must stop this room before delete it');
            $room->delete();
            return self::responseJSON(204, true, 'Delete room success');
        } catch (AuthorizationException $e) {
            return self::response403($e->getMessage());
        } catch (\Exception $e) {
            return self::responseJSON(500, false, 'Server error');
        }
    }

    public function restore(Request $request, Room $room)
    {

    }
}
