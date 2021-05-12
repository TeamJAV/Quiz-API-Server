<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Repositories\Room\RoomRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentLoginController extends Controller
{
    //
    private $roomRepo;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepo = $roomRepository;
    }

    public function loginRoom(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('layouts.Student.login-room');
        }
        $request->validate(['room-name' => 'required|min:1|max:25']);
        $room = Room::query()->where('name', $request->get('room-name'))->first();
        if (!$room) {
            return redirect()->back()->withErrors("Don't have this room.Please try again!", "room-name");
        }
        $request->session()->put('room', ['id' => $room->id, 'name' => $room->name]);
        return redirect()->route('student.login.student-name');
    }

    public function loginStudentName(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('layouts.Student.login-student-name');
        }
        $request->validate(['student-name' => 'required|min:3|max:25']);
        $request->session()->put('student', ['name' => $request->get('student-name'), 'created_at' => Carbon::now()]);
        return redirect()->route('student.join', $request->session()->get('room')['id']);
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->session()->forget('room');
            $request->session()->forget('student');
            return redirect()->route('student.login.room');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}
