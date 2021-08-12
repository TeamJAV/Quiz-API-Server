<?php


namespace App\Repositories\QuizCopy;


use App\Models\QuizCopy;
use App\Repositories\BaseRepository;

class QuizCopyRepository extends BaseRepository implements IQuizCopyRepositoryInterface
{
    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return QuizCopy::class;
    }

    public function createQuestionCopy($quiz_copy, $collections): bool
    {
        try {
            $collections->each(function ($item, $index) use ($quiz_copy) {
                $quiz_copy->questionCopies()->create([
                    'title' => $item->title,
                    'explain' => $item->explain,
                    'choices' => $item->choices,
                    'correct_choices' => $item->correct_choices,
                    'question_type' => $item->question_type,
                    'img' => $item->img
                ]);
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }
}
