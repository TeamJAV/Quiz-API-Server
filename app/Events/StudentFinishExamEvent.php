<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentFinishExamEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $_timestamp;

    /**
     * Create a new event instance.
     *
     * @param $_timestamp
     */
    public function __construct($_timestamp)
    {
        //
        $this->_timestamp = $_timestamp;
    }

    public function broadcastAs(): string
    {
        return 'event-student-finished-exam';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('student-finished-exam.' . $this->_timestamp);
    }

    public function broadcastWith(): array
    {
        return [
            "is_finished" => true,
            "time_end_at" => Carbon::createFromTimestamp($this->_timestamp)->format('Y-m-d H:s:i'),
        ];
    }
}
