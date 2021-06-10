<?php


namespace App\Repositories\QuestionCopy;


use App\Models\QuestionCopy;
use App\Repositories\BaseRepository;
use App\Repositories\QuizCopy\IQuizCopyRepositoryInterface;

class QuestionCopyRepository extends BaseRepository implements IQuizCopyRepositoryInterface
{

    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return QuestionCopy::class;
    }

    public function getAllQuestionCopyByQuizId($quiz_copy_id)
    {
        return $this->model->where('quiz_copy_id', $quiz_copy_id)->get();
    }

    public function getAllQuestionCopyByQuizIdJsonDecode($quiz_copy_id)
    {
        $questions = $this->model->where('quiz_copy_id', $quiz_copy_id)->get();
        $questions->transform(function ($item, $index) {
            $item->choices = collect(json_decode($item->choices))->sortKeys();
            $item->correct_choices = collect(json_decode($item->correct_choices))->sort();
            return $item;
        });
        return $questions;
    }
}
