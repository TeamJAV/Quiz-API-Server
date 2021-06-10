<?php

namespace App\Policies;

use App\Models\Quiz;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class QuizPolicy
 * @package App\Policies
 */
class QuizPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Quiz $quiz
     * @return mixed
     */
    public function view(User $user, Quiz $quiz)
    {
        //
        return $user->id = $quiz->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Quiz $quiz
     * @return mixed
     */
    public function update(User $user, Quiz $quiz)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Quiz $quiz
     * @return mixed
     */
    public function delete(User $user, Quiz $quiz)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Quiz $quiz
     * @return mixed
     */
    public function restore(User $user, Quiz $quiz)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Quiz $quiz
     * @return mixed
     */
    public function forceDelete(User $user, Quiz $quiz)
    {
        //
    }
}
