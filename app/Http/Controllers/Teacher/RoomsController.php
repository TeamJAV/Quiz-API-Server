<?php

namespace App\Http\Controllers\Teacher;

use App\Events\RoomOnlineEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Student\WaitingRoomController;
use App\Http\Requests\Room\NewRoomRequest;
use App\Models\Room;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|Response|View
     */
    public function index(Request $request)
    {
        //
        $rooms = Room::query()->where('user_id', auth()->id())->orderByDesc('status');
        if ($request->filled('s')){
            $rooms->where('name', 'like', "%". $request->get('s') ."%");
        }
        return view('layouts.Teacher.rooms', ['rooms' =>  $rooms->simplePaginate(5), 's' => $request->filled('s') ? $request->get('s') : '']);
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
        auth()->user()->rooms()->create([
            'name' => $request->get('name'),
            'status' => $request->filled('status') ? 1 : 0,
            'required_name' => 0,
            'is_shuffle' => $request->filled('is_shuffle') ? 1 : 0
        ]);
        $request->session()->flash('success', "Room {$request->get('required_name')} have been created");
        return Controller::responseJSON();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        //
        $room = Room::findOrFail($id);
        try {
            $room->status = $room->status == 0 ? 1 : 0;
            $room->save();
            $type = $room->status == 1 ? "online" : "offline";
            $request->session()->flash('success', "Room {$room->name} has {$type}");
            event(new RoomOnlineEvent($room->id, $room->status == 1));
            return Controller::responseJSON();
        }catch (\Exception $e) {
            return Controller::responseJSON(500, false, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     *
     */
    public function destroy(Request $request, $id)
    {
        //
        try {
            $room = Room::find($id);
            $room_name = $room->name;
            if ($room->status == 0){
                $room->delete();
                $request->session()->flash('success', "Room {$room_name} have been deleted");
            }
        }catch (\Exception $e){
            $request->session()->flash('error', $e->getMessage());
        }
    }
}
