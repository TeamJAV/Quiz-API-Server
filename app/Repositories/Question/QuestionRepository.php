<?php


namespace App\Repositories\Question;


use App\Models\Question;
use App\Repositories\BaseRepository;

class QuestionRepository extends BaseRepository implements IQuestionRepositoryInterface
{

    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return Question::class;
    }

    public function getAllQuestionsByQuizId($quiz_id)
    {
        return $this->model->where('quiz_id', $quiz_id)->get();
    }

    public function getAllQuestionsByQuizIdJsonDecode($quiz_id)
    {
        $questions = $this->model->where('quiz_id', $quiz_id)->get();
        $questions->transform(function ($item, $index) {
            $item->choices = collect(json_decode($item->choices))->sortKeys();
            $item->correct_choices = collect(json_decode($item->correct_choices))->sort();
            return $item;
        });
        return $questions;
    }
}
