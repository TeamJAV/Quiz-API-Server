<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultDetailCollectionLive extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $choices = json_decode($this->student_choices);
        $choices = array_map(function ($r) {
            $r = (array) $r;
            $id = array_key_first($r);
            $r["question_id"] = $id;
            $r["student_choice"] = $r[$id];
            unset($r[$id]);
            return $r;
        }, $choices);
        return [
            'id' => $this->id,
            'student_name' => $this->student_name,
            'student_choices' => $choices
        ];
    }
}
