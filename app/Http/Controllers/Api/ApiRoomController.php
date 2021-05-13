<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\NewRoomRequest;
use App\Http\Resources\RoomCollection;
use App\Models\Room;
use App\Repositories\Room\RoomRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class ApiRoomController extends Controller
{
    //
    private $roomRepository;
    private $userRepository;

    public function __construct(RoomRepository $roomRepository, UserRepository $userRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request, $search = null): \Illuminate\Http\JsonResponse
    {
        $rooms = $this->roomRepository->getRoomByNamePaginate(auth()->id(), $search);
        return self::responseJSON(200, true, 'List all rooms', RoomCollection::collection($rooms));
    }

    public function store(NewRoomRequest $request): \Illuminate\Http\JsonResponse
    {
        if ($request->filled('id')) {
            auth()->user()->rooms()->where('id', $request->get('id'))->update(["name" => $request->get('name')]);
            return self::responseJSON(200, true, 'Update success', new RoomCollection(auth()->user()->rooms()->where('id', $request->get('id'))->first()));
        } else {
            $room = auth()->user()->rooms()->create(["name" => $request->get("name")]);
            return self::responseJSON(200, true, 'Create success', new RoomCollection($room));
        }
    }

}
