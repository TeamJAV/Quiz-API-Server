<?php


namespace App\Repositories\Room;

use App\Repositories\IRepositoryInterface;

interface IRoomRepositoryInterface extends IRepositoryInterface
{
    public function getRoom();
}
