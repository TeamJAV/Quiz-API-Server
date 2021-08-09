<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class RoomCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "status" => $this->status != 0 ? 'Online' : 'Offline',
            "shuffle_answer" => $this->shuffle_answer != 0,
            "shuffle_question" => $this->shuffle_question != 0,
            "time_offline" => $this->time_offline
        ];
    }
}
