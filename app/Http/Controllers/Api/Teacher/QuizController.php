<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\QuestionRequest;
use App\Http\Resources\QuizCollection;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends ApiBaseController
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

    public function editQuiz(Request $request, $id)
    {
        $quiz = auth()->user()->quizzes()->find($id)->first();
        if (!$quiz) {
            return self::responseJSON(500, false, 'Không tìm thấy quiz.');
        }
        if ($request->quiz_title == null) {
            return self::responseJSON(500, false, 'Tên không được để trống');
        }
        $quiz->title = $request->quiz_title;
        $quiz->save();
        return self::responseJSON(200, true, 'Cập nhật thành công', $quiz);
    }

    public function deleteQuiz(Request $request, $id)
    {
        $quiz = auth()->user()->quizzes()->find($id)->first();
        if (!$quiz) {
            return self::responseJSON(500, false, 'Không tìm thấy quiz.');
        }
        $quiz->delete();
        return self::responseJSON(200, true, 'Xóa thành công');
    }

    public function showAllQuestion(Request $request)
    {
        $question = Question::all();
        return self::responseJSON(200, true, "Thành công", $question);
    }

    public function createQuestion(QuestionRequest $request, $id): JsonResponse
    {
        $quiz = auth()->user()->quizzes()->find($id)->first();
        if (!$quiz) {
            return self::responseJSON(500, false, 'Không tìm thấy quiz.');
        }
        $question = Question::create(
            ['title' => $request->get('title'),
                'explain' => $request->get('explain'),
                'choices' => $request->get('choices'),
                'correct_choices' => $request->get('correct'),
                'question_type' => $request->get('question_type'),
                'quiz_id' => $quiz->id]
        );

        return Controller::responseJSON(200, true);
    }

    public function editQuestion(QuestionRequest $request, $id): JsonResponse
    {
        $question = Question::find($id);
        if (!$question) {
            return self::responseJSON(500, false, 'Không tìm thấy question.');
        }
        $question->title = $request->title;
        $question->explain = $request->explain;
        $question->choices = $request->choices;
        $question->correct_choices = $request->correct;
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
}
