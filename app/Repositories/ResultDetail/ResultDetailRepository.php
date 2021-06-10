<?php


namespace App\Repositories\ResultDetail;


use App\Repositories\BaseRepository;

class ResultDetailRepository extends BaseRepository implements IResultDetailRepositoryInterface
{

    public function getModel(): string
    {
        // TODO: Implement getModel() method.
        return \App\Models\ResultDetail::class;
    }


}
