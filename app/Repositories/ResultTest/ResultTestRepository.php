<?php


namespace App\Repositories\ResultTest;


use App\Repositories\BaseRepository;
use App\Repositories\QuestionCopy\QuestionCopyRepository;
use Illuminate\Support\Carbon;

class ResultTestRepository extends BaseRepository implements IResultTestRepositoryInterface
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
        return \App\Models\ResultTest::class;
    }

    public function getResultTestOnline($id)
    {
        return $this->model->where("status", 1)->where("room_id", $id)->first();
    }

    public function creatResultDetailForStudent($quiz_copy_id, $result_test, $student_name, $time_offline)
    {
        $question_copies = $this->questionCopyRepository->getAllQuestionCopyByQuizIdJsonDecode($quiz_copy_id);
        $question_copies->transform(function ($item, $index) {
            return [
                $item->id => [
                    "choices" => [],
                    "correct" => false,
                    "type" => $item->question_type,
                ]
            ];
        });
        $now = Carbon::now();
        $end = $now->addMinutes($time_offline);
        return $result_test->resultDetails()->create([
            'student_name' => $student_name,
            'scores' => 0,
            'time_joined' => $now->format("Y-m-d H:i:s"),
            'student_choices' => json_encode($question_copies),
            'time_end' => $end->second(0)->format("Y-m-d H:i:s"),
            'timestamp_out' => $end->second(0)->timestamp
        ]);
    }

    public function changeStatusDetails($id)
    {
        $this->find($id)->resultDetails()->where('is_finished', 0)->update(['is_finished' => 1]);
    }
}
