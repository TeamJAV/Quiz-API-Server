<?php


namespace App\Repositories\Quiz;


use App\Models\Quiz;
use App\Repositories\BaseRepository;
use App\Repositories\Room\IRoomRepositoryInterface;

/**
 * Class QuizRepository
 * @package App\Repositories\Quiz
 */
class QuizRepository extends BaseRepository implements IQuizRepositoryInterface
{

    /**
     * @return string
     */
    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return Quiz::class;
    }

}
