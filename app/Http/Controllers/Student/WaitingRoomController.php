<?php

namespace App\Http\Controllers\Student;

use App\Events\RoomOnlineEvent;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class WaitingRoomController extends Controller
{
    //

    public function index(Request $request){
        if (Room::find($request->session()->get('room')['id'])->status == 1){
            return redirect()->route('student.join', $request->session()->get('room')['id']);
        }
        return view('layouts.Student.waiting');
    }

}
