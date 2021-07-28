<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

// Channel status of room
Broadcast::channel('room.{id}', function ($id) {
    return \App\Models\Room::where('id', decrypt($id))->exists();
});

// Channel result for student when cronjob run
Broadcast::channel("result_detail.{id}", \App\Broadcasting\ResultDetailChannel::class);
