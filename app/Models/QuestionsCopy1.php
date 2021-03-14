<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionsCopy1 extends Model
{
    //
    public function quizCopy1(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizCopy1::class, 'quiz1_id');
    }
}
