<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Question;
use App\Models\ResultDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiQuizController extends ApiBaseController
{
    //
    public function __construct()
    {

    }

    public function index()
    {
        // Get all question from quiz && format to collection
        $question = Question::query()->where('quiz_id', 1)->get();
        $question->transform(function ($item, $index) {
            $item->choices = collect(json_decode($item->choices))->sortKeys();
            $item->correct_choices = collect(json_decode($item->correct_choices))->sort();
            return $item;
        });

        // Format user submit choices
        $student_input = "{\"1\": [\"C\"]}";
        $student_input = collect(json_decode($student_input))->transform(function ($item, $index) {
            return collect($item)->sort();
        });
        // Format user submit into db
        $question_true = $student_input->map(function ($item, $key) use ($question) {
            return $item == $question->filter(function ($value, $k) use ($key) {
                    if ($value->id == $key) return $value;
                    return null;
                })->first()->correct_choices;
        });
        dd($question_true);
    }

    public function check(Request $request)
    {
        $result_details = ResultDetail::query()
            ->where("is_finished", 0)
            ->whereRaw("DATE_FORMAT(time_end, '%Y-%m-%d\ %H:%i') = ?", Carbon::now()->format("Y-m-d H:i"))
            ->get();
        foreach ($result_details as $result_detail) {
            $time_end = Carbon::parse($result_detail->time_end)->format("Y-m-d H:i");
        }
    }
}
