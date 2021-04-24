<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizzesController extends Controller
{
    //
    protected $quiz;


    public function __construct(Quiz $quiz) {
        $this->quiz = $quiz;
    }

    public function index(Request  $request) {
        return view('layouts.Teacher.quizzes');
    }


    public function create(Request $request) {
        return view('layouts.Teacher.create-quiz');

    }


    public function store(Request $request) {
        

    }
}
