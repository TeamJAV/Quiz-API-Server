<?php


namespace App\Repositories\Room;

use App\Repositories\BaseRepository;

class RoomRepository extends BaseRepository implements IRoomRepositoryInterface
{
    public function getModel(): string
    {
        return \App\Models\Room::class;
    }

    public function getRoom()
    {
        // TODO: Implement getRoom() method.
        return $this->model->take(5)->get();
    }

    public function getRoomByUserPaginate($userId)
    {
        return $this->model->with("users")->where("user_id", $userId)->orderBy("id")->paginate($this->perPage);
    }

    public function getRoomByNamePaginate($userId, $name = null)
    {
        if (is_null($name)) {
            return $this->getRoomByUserPaginate($userId);
        }
        $name = strtoupper($name);
        return $this->model->where("user_id", $userId)->where('name', 'like', "%" . $name . "%")
            ->orderBy("id")->paginate($this->perPage);
    }
}
