<?php

namespace App\Events;

use App\Models\ResultDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResultStudentReceiveEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $result_detail;

    /**
     * Create a new event instance.
     *
     * @param ResultDetail $resultDetail
     */
    public function __construct(ResultDetail $resultDetail)
    {
        //
        $this->result_detail = $resultDetail;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('result_detail.' . encrypt($this->result_detail->id));
    }

    public function broadcastAs(): string
    {
        return 'result_student_receive';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->result_detail->id,
            'student_name' => $this->result_detail->student_name,
            'scores' => $this->result_detail->scores,
            'time_joined' => $this->result_detail->time_joined,
            'time_end' => $this->result_detail->time_end,
            'result_test_id' => $this->result_detail->result_id,
        ];
    }
}
