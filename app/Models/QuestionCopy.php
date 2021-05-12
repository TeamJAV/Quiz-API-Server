<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionCopy extends Model
{
    //
    use SoftDeletes;
    
    public function quizCopy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizCopy::class, "quiz_copy_id");
    }
}
