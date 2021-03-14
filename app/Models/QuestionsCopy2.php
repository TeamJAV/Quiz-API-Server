<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionsCopy2 extends Model
{
    //
    public function quizCopy2(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizCopy2::class, 'quiz2_id');
    }
}
