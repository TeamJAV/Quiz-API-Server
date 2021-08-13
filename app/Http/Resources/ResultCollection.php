<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quiz_copy_id' => $this->quiz_copy_id,
            'room_id' => $this->room_id,
            'date_create' => $this->date_create,
            'result' => $this->resultDetails,
//            'result' => ResultCollection::collection($this->resultDetails),
        ];
    }
}
