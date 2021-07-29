<?php

namespace App\Policies;

use App\Models\Room;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(): bool
    {
        return true;
    }

    public function view(User $user, Room $room): bool
    {
        return $user->id == $room->user_id;
    }

    public function update(User $user, Room $room): bool
    {
        return $user->id == $room->user_id;
    }

    public function share(User $user, Room $room): bool
    {
        return $user->id == $room->user_id;
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->id == $room->user_id;

    }

}
