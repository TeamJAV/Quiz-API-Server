<?php

use Carbon\Carbon;
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
    return \App\Models\Room::query()->where("id", $id)->exists();
});
// Channel stop exam in group timestamp
Broadcast::channel('student-finished-exam.{timestamp}', function ($timestamp) {
    try {
        Carbon::createFromTimestamp($timestamp);
        return true;
    } catch (Exception $e) {
        return false;
    }
});

// Channel result for student when cronjob run
Broadcast::channel("result-detail.{id}", \App\Broadcasting\ResultDetailChannel::class);
