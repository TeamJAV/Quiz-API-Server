<?php


namespace App\Repositories\ResultDetail;


use App\Repositories\BaseRepository;
use App\Repositories\QuestionCopy\QuestionCopyRepository;
use Illuminate\Support\Carbon;

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

    public function getStudentWaitFromRoom(int $room_pending_id)
    {
        return $this->model->where("room_pending_id", $room_pending_id)->first();
    }

    public function existsDuplicateStudentName($student_name, $result_id, $room_pending_id): bool
    {
        $q = $this->model->where("student_name", $student_name);
        if ($room_pending_id != null && $result_id == null) {
            return $q->where("room_pending_id", $room_pending_id)->exists();
        }
        if ($room_pending_id == null && $result_id != null) {
            return $q->where('result_id', $result_id)->exists();
        }
        return false;
    }

    public function updateStudentWaiting($room_pending_id, $default_choices, $result_test, $time_offline)
    {
        $end = $time_offline != null
            ? Carbon::now()->addMinutes($time_offline)->setSecond(0)
            : null;
        $this->model
            ->where("room_pending_id", $room_pending_id)
            ->whereNull("student_choices")
            ->update([
                'student_choices' => $default_choices,
                'result_id' => $result_test->id,
                'time_joined' => Carbon::now()->format("Y-m-d H:i:s"),
                'time_end' => $end != null ? $end->format("Y-m-d H:i:s") : null,
                'timestamp_out' => $end != null ? $end->timestamp : null,
                'room_pending_id' => null
            ]);
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
            $id = (int)key($item);
            $content = $item->$id;
            if ($content) {
                if ($id == $question_id) {
                    $content->choices = $answer['choices'];
                    $content->correct = $is_correct();
                }
                if ($content->correct) $count_true += 1;
            } else {
                $count_true = $result_detail->scores;
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
        $string = preg_replace("/\s+/", "", $string);
        $string = preg_replace("/\t+/", "", $string);
        $string = preg_replace("/\n\r+/", "", $string);
        return $string;
    }

    public function formatResultDetail($result_details)
    {
        return $result_details->transform(function ($record) {
            $record->student_choices = json_decode($record->student_choices, true);
            $record->student_choices = array_map(function ($r) {
                $id = array_key_first($r);
                $r["question_id"] = $id;
                $r["student_choice"] = $r[$id];
                unset($r[$id]);
                return $r;
            }, $record->student_choices);
            unset($record["deleted_at"]);
            unset($record["created_at"]);
            unset($record["updated_at"]);
            unset($record["room_pending_id"]);
            return $record;
        })->toArray();
    }
}
