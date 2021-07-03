<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionCopyCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'choices'=>json_decode($this->choices),
            'explain'=>$this->explain,
            'correct'=>json_decode($this->correct_choices),
            'question_type'=>$this->question_type,
//            'img'=>$this->img != null ? public_path('storage/'.$this->img) : null,
            'img'=>$this->img,

        ];
    }
}
