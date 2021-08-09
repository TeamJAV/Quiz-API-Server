<?php


namespace App\Repositories\ResultDetail;


use App\Repositories\BaseRepository;
use App\Repositories\QuestionCopy\QuestionCopyRepository;

class ResultDetailRepository extends BaseRepository implements IResultDetailRepositoryInterface
{
    private $questionCopyRepository;

    public function __construct(QuestionCopyRepository $questionCopyRepository)
    {
        $this->questionCopyRepository = $questionCopyRepository;
        parent::__construct();
    }

    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return \App\Models\ResultDetail::class;
    }

    public function solveResult($result_detail, $answer, $quiz_copy_id)
    {
        $question_copies = $this->questionCopyRepository->getAllQuestionCopyByQuizIdJsonDecode($quiz_copy_id);
        $question_id = $answer['question_id'];
        $current_question_copies = $question_copies->filter(function ($item) use ($question_id) {
            return $item->id == $question_id;
        })->first();
        $student_choices = json_decode($result_detail->student_choices);
        $is_correct = function () use ($current_question_copies, $answer) {
            $correct = false;
            if ($current_question_copies->question_type == "multiple" || $current_question_copies->question_type == "true-false") {
                $correct = $this->_arrayEqual($current_question_copies->correct_choices->toArray(), $answer['choices']);
            } elseif ($current_question_copies->question_type == "short-answer") {
                $correct_choices = $current_question_copies->correct_choices->map(function ($item) {
                    return $this->_clearString($item);
                });
                $correct = in_array($this->_clearString($answer['choices'][0]), $correct_choices->toArray());
            }
            return $correct;
        };
        $count_true = 0;
        foreach ($student_choices as $index => &$item) {
            if (isset($item->$question_id)) {
                $content = $item->$question_id;
                $content->choices = $answer['choices'];
                $content->correct = $is_correct();
                if ($content->correct) $count_true += 1;
            }
        }
        $result_detail->scores = $count_true;
        $result_detail->student_choices = json_encode($student_choices);
        $result_detail->save();
    }

    private function _arrayEqual($a, $b): bool
    {
        return (
            is_array($a)
            && is_array($b)
            && count($a) == count($b)
            && array_diff($a, $b) === array_diff($b, $a)
        );
    }

    private function _clearString($string)
    {
        $string = trim($string);
        $string = preg_replace("/\s+/", " ", $string);
        $string = preg_replace("/\t+/", " ", $string);
        $string = preg_replace("/\n\r+/", " ", $string);
        return $string;
    }
}
