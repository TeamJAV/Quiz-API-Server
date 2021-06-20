<?php

namespace App\Broadcasting;

use App\Models\ResultDetail;
use App\User;
use Illuminate\Contracts\Encryption\DecryptException;

class ResultDetailChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @param $id
     * @return array|bool
     */
    public function join(User $user, $id)
    {
        //
        try {
            $id = decrypt($id);
            return ResultDetail::findOrFail($id) == 1;
        } catch (DecryptException $e) {
            return false;
        }
    }
}
