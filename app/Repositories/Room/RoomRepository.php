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

    public function getRoomByUserPaginate($userId, $orderBy, $type, $trash)
    {
        $model = is_null($trash) ? $this->model : $this->model->onlyTrashed();
        return $model->with("user")->where("user_id", $userId)->orderByRaw($orderBy . " " . $type)->paginate($this->perPage);
    }

    public function getRoomByNamePaginate($userId, $name, $orderBy, $type, $trash, $paginate = true)
    {
        if (is_null($name)) {
            return $this->getRoomByUserPaginate($userId, $orderBy, $type, $trash);
        }
        $name = strtoupper($name);
        $model = is_null($trash) ? $this->model : $this->model->onlyTrashed();
        $data = $model->with('user')->where("user_id", $userId)->where('name', 'like', "%" . $name . "%")
        ->orderByRaw($orderBy . " " . $type);
        if  ($paginate) {
            return $data->paginate($this->perPage);
        }
        return $data;
    }

    public function getRoomByName($name)
    {
        $name = strtoupper($name);
        return $this->model->with("user")->where("name", "like", $name)->first();
    }

    public function setUpRoomOnline($id, $attributes = [])
    {
        $room = $this->find($id);
        if ($room->user_id != auth()->id())
            return false;
        return $this->update($id, $attributes);
    }

    public function setUpRoomOffline($id)
    {
        return $this->update($id, [
            'status' => 0,
            'shuffle_answer' => 0,
            'shuffle_question' => 0,
            'time_offline' => null
        ]);
    }
}
