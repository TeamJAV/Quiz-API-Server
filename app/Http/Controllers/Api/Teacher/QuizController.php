<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\QuestionCollection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\QuestionRequest;
use App\Http\Resources\QuizCollection;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Exports\ExcelExports;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function showAllQuiz(Request $request)
    {
        $quizzes = auth()->user()->quizzes()->get();
        return self::responseJSON(200, true, 'Thành công', QuizCollection::collection($quizzes));
    }

    public function createQuiz(Request $request)
    {
        $room = auth()->user()->quizzes()->create(['title' => 'Untitled Quiz']);
        return self::responseJSON(200, true, 'Tạo thành công', $room);
    }

    public function getQuizById(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return self::responseJSON(404, false, 'Không tìm thấy quiz!');
        }
        if (!auth()->user()->can("view", $quiz)) {
            return self::responseJSON(403, false, 'Không tìm thấy quiz!');
        }
        return self::responseJSON(200, true, 'Thành công', new QuizCollection($quiz));
    }

    public function editQuiz(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return self::responseJSON(404, false, 'Không tìm thấy quiz.');
        }
        if (!auth()->user()->can("editQuiz", $quiz)) {
            return self::responseJSON(403, false, 'Không tìm thấy quiz!');
        }
        if ($request->quiz_title == null) {
            return self::responseJSON(500, false, 'Tên không được để trống!');
        }
        $quiz->title = $request->quiz_title;
        if (count($quiz->questions) == 0) {
            return self::responseJSON(500, false, 'Quiz cần có ít nhất 1 câu hỏi');
        }
        $quiz->save();
        return self::responseJSON(200, true, 'Cập nhật thành công', $quiz);
    }

    public function searchQuiz(Request $request, $title = null)
    {
        $search_result = Quiz::query()->where('user_id', '=', Auth::id());
        if ($title == null) {
            $search_result = $search_result->get();
        } else {
            $search_result = $search_result->where('title', 'like', '%' . $title . '%')->get();
            if (!$search_result) {
                return self::responseJSON(404, false, 'Không có kết quả cho ' . $title);
            }
        }
        return self::responseJSON(200, true, 'Tìm kiếm thành công', QuizCollection::collection($search_result));
    }

    public function deleteQuiz(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return self::responseJSON(404, false, 'Không tìm thấy quiz.');
        }
        if (!auth()->user()->can("delete", $quiz)) {
            return self::responseJSON(403, false, 'Không tìm thấy quiz!');
        }
        $quiz->delete();
        return self::responseJSON(200, true, 'Xóa thành công');
    }

    public function showAllQuestion(Request $request, $id)
    {
        $quiz = auth()->user()->quizzes()->where('id', $id)->first();
        if (!$quiz) {
            return self::responseJSON(404, false, 'Không tìm thấy quiz.');
        }
        $question = Question::query()->where("quiz_id", $quiz->id)->get();
        return self::responseJSON(200, true, "Thành công", QuestionCollection::collection($question));
    }

    public function createQuestion(QuestionRequest $request, $id): JsonResponse
    {
        $question_img = null;
        $quiz = auth()->user()->quizzes()->where('id', $id)->first();
        if (!$quiz) {
            return self::responseJSON(500, false, 'Không tìm thấy quiz.');
        }
//        if ($request->img != null) {
//            $question_img = $request->img->store('image', 'public');
//        }
        if ($request->hasFile("img")) {
            $validator = Validator::make($request->only("img"), [
                'img' => 'nullable|mimes:jpeg,bmp,png',
            ]);
            if ($validator->fails()) {
                return self::responseJSON(422, false, $$validator->errors()->first());
            }
            $question_img = $request->file("img")->store('image', 'public');
        }
        if ($request->question_type == 'multiple') {
            if (count((array)json_decode($request->choices)) < 2) {
                return self::responseJSON(500, false, 'Cần có ít nhất 2 câu trả lời.');
            }
        }
        $question = Question::create(
            ['title' => $request->get('title'),
                'explain' => $request->get('explain'),
                'choices' => $request->get('choices'),
                'correct_choices' => $request->get('correct'),
                'quiz_id' => $quiz->id,
                'question_type' => $request->get('question_type'),
                'img' => $question_img,
            ]
        );

        $q = Question::with('quiz')->find($question->id);
        return Controller::responseJSON(200, true, "Tạo thành công", new QuestionCollection($q));
    }

    public function editQuestion(QuestionRequest $request, $id): JsonResponse
    {
        $question_img = null;
//        if ($request->img != null) {
//            $question_img = $request->img->store('image', 'public');
//        }
        if ($request->hasFile("img")) {
            $validator = Validator::make($request->only("img"), [
                'img' => 'nullable|mimes:jpeg,bmp,png',
            ]);
            if ($validator->fails()) {
                return self::responseJSON(422, false, $$validator->errors()->first());
            }
            $question_img = $request->file("img")->store('image', 'public');
        }
        $question = Question::find($id);
        if (!$question) {
            return self::responseJSON(500, false, 'Không tìm thấy question.');
        }
        $question->title = $request->title;
        $question->explain = $request->explain;
        $question->choices = $request->choices;
        $question->correct_choices = $request->correct;
        $question->question_type = $request->question_type;
        $question->img = $question_img;
        $question->save();
        return Controller::responseJSON(200, true, "", $question);
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return self::responseJSON(500, false, 'Không tìm thấy question.');
        }
        $question->delete();
        return Controller::responseJSON(200, true, "Xóa thành công");
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ExcelExports($request), 'export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv',]);
    }

}
