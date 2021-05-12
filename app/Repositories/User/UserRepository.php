<?php


namespace App\Repositories\User;


use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements IUserRepositoryInterface
{

    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return \App\User::class;
    }

    public function getByEmail($email)
    {
        return $this->model->where("email", $email)->first();
    }
}
