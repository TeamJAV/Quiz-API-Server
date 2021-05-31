<?php


namespace App\Repositories\ResultTest;


use App\Repositories\BaseRepository;

class ResultTestRepository extends BaseRepository implements IResultTestRepositoryInterface
{

    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return \App\Models\ResultTest::class;
    }

    public function getResultTestOnline($id)
    {
        return $this->model->where("status", 1)->where('room_id', $id)->first();
    }

}
