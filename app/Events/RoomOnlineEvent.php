<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomOnlineEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $room_id;
    public $online;
    public $shuffle_question;
    public $shuffle_answer;
    public $time_end;
    public $is_test;

    public function __construct($room, $is_test = false)
    {
        //
        $this->room_id = $room->id;
        $this->online = $room->status != 0;
        $this->shuffle_question = $room->shuffle_question != 0;
        $this->shuffle_answer = $room->shuffle_answer != 0;
        $this->time_end = $room->time_end;
        $this->is_test = $is_test;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.' . encrypt($this->room_id));
    }


    public function broadcastAs(): string
    {
        return 'room.online.event';
    }

    public function broadcastWith(): array
    {
        if ($this->online) {
            return [
                'message' => 'Room online, go to test',
                'room' => [
                    'id' => $this->room_id,
                ],
                'is_online' => $this->online,
                'is_shuffle_question' => $this->shuffle_question,
                'is_shuffle_answer' => $this->shuffle_answer,
                'time_out' => $this->time_end
            ];
        } else {
            return [
                'message' => 'Room offline, go to loading',
                'room' => [
                    'id' => $this->room_id,
                ],
                'is_online' => $this->online,
                'is_test' => $this->is_test
            ];
        }
    }
}
