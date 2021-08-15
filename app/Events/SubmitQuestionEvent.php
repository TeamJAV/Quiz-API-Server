<?php

namespace App\Events;

use App\Models\QuestionCopy;
use App\Models\ResultDetail;
use App\Models\ResultTest;
use App\Repositories\ResultDetail\ResultDetailRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmitQuestionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $resultTest;

    /**
     * Create a new event instance.
     *
     * @param ResultTest $resultTest
     */
    public function __construct(ResultTest $resultTest)
    {
        $this->resultTest = $resultTest;
    }

    public function broadcastAs(): string
    {
        return 'event-result-teacher';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('result-teacher.' . $this->resultTest->id);
    }

    public function broadcastWith(): array
    {
        $resultDetail = $this->resultTest->resultDetails()->get();
        $repo = app("resultDetailRepo");
        return [
            'result_live' => $repo->formatResultDetail($resultDetail),
            'percent' => $repo->getPercent($resultDetail)
        ];
    }
}
