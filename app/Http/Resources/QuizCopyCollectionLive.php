<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizCopyCollectionLive extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'number_question' => count($this->questionCopies),
            'questions' => QuestionCopyCollectionShort::collection($this->questionCopies),
        ];
    }
}
