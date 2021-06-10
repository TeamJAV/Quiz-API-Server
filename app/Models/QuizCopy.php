<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizCopy extends Model
{
    //
    use SoftDeletes;

    protected $guarded = [];

    public function quiz(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Quiz::class, "quiz_id");
    }

    public function resultTests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultTest::class, "quiz_copy_id");
    }

    public function questionCopies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuestionCopy::class, "quiz_copy_id");
    }
}
