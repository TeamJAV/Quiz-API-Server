<?php

namespace App\Http\Controllers\Teacher;

use App\Events\RoomOnlineEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Student\WaitingRoomController;
use App\Http\Requests\Room\NewRoomRequest;
use App\Models\Room;
use App\Repositories\Room\RoomRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class RoomsController extends Controller
{
    private $roomRepo;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepo = $roomRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|JsonResponse|View
     * @throws Throwable
     */
    public function index(Request $request)
    {
        //
        $rooms = $this->roomRepo->getRoomByNamePaginate(auth()->id(), $request->get("s"));
        $context = [
            "rooms" => $rooms,
            "s" => $request->filled('s') ? $request->get('s') : ''
        ];
        return view('layouts.Teacher.rooms', $context);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        //
        return view('includes.Teacher.create-room');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewRoomRequest $request
     * @return JsonResponse
     */
    public function store(NewRoomRequest $request): JsonResponse
    {
        //
        auth()->user()->rooms()->create(["name" => strtoupper($request->get("name"))]);
        $request->session()->flash('success', "Room {$request->get('required_name')} have been created");
        return Controller::responseJSON();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|Response|View
     */
    public function edit(int $id)
    {
        //
        return view('includes.Teacher.edit-room', ['room' => Room::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NewRoomRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(NewRoomRequest $request, $id): JsonResponse
    {
        //
//        $room = Room::findOrFail($id);
//        try {
//            $room->status = $room->status == 0 ? 1 : 0;
//            $room->save();
//            $type = $room->status == 1 ? "online" : "offline";
//            $request->session()->flash('success', "Room {$room->name} has {$type}");
//            event(new RoomOnlineEvent($room->id, $room->status == 1));
//            return Controller::responseJSON();
//        }catch (\Exception $e) {
//            return Controller::responseJSON(500, false, $e->getMessage());
//        }
//        Room::where('id', $id)->update(['name' => $request->get('name')]);
        $this->roomRepo->update($id, ["name" => strtoupper($request->get("name"))]);
        $request->session()->flash('success', "Rename room success");
        return Controller::responseJSON();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        //
        $check = $this->roomRepo->delete($id);
        if ($check) {
            $request->session()->flash('success', "Room have been deleted");
            return Controller::responseJSON();
        }
        $request->session()->flash('error', "Something went wrong");
        return Controller::responseJSON(402, false);
    }

}
