<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionCollection extends JsonResource
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
            'quiz'=>$this->quiz,
            'title'=>$this->title,
            'choices'=>$this->choices,
            'explain'=>$this->explain,
            'correct'=>$this->correct_choices,
//            'img'=>$this->img != null ? public_path('storage/'.$this->img) : null,
            'img'=>public_path().'/storage/'.$this->img,

        ];
    }
}
