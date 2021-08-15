<?php

namespace App\Http\Resources\HistoryTest;

use App\Http\Resources\QuizCopyCollectionLive;
use App\Http\Resources\ResultDetailCollectionLive;
use App\Http\Resources\RoomCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultTestCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'start_at' => Carbon::parse($this->date_create)->toDayDateTimeString(),
            'stop_at' => Carbon::parse($this->updated_at)->toDayDateTimeString(),
            'room' => new RoomCollection($this->room),
            'quiz' => new QuizCopyCollectionLive($this->quizCopy),
            'result' => ResultDetailCollectionLive::collection($this->resultDetails)
        ];
    }
}
