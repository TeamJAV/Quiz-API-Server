<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionCopy extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'id',
        "title", "explain", "choices", "correct_choices", "question_type", "quiz_copy_id", "img"
    ];
    public function quizCopy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizCopy::class, "quiz_copy_id");
    }
}
